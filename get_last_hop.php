<?php

define ("SOL_IP", 0);
define ("IP_TTL", 4);    // On OSX, use '4' instead of '2'.

function get_last_hop($ip) {

//// A specialized get_last_hop in which if there is a timeout we are assuming it is happening at the destination IP, and returning the IP before it. 
//// If during the trace we get to the destination, then we simply return $prev_addr


    
    $dest_url = $ip;   // Fill in your own URL here, or use $argv[1] to fetch from commandline.
    $maximum_hops = 10;
    $port = 33434;  // Standard port that traceroute programs use. Could be anything actually.
    $recv_addr = '';
    
    // Get IP from URL
    $dest_addr = gethostbyname ($dest_url);
    //print "Tracerouting to destination: $dest_addr\n";
    
    $ttl = 1;
    while ($ttl < $maximum_hops) {
        $prev_addr = $recv_addr;
        // Create ICMP and UDP sockets
        $recv_socket = socket_create (AF_INET, SOCK_RAW, getprotobyname ('icmp'));
        $send_socket = socket_create (AF_INET, SOCK_DGRAM, getprotobyname ('udp'));
    
        // Set TTL to current lifetime
        socket_set_option ($send_socket, SOL_IP, IP_TTL, $ttl);
    
        // Bind receiving ICMP socket to default IP (no port needed since it's ICMP)
        socket_bind ($recv_socket, 0, 0);
    
        // Save the current time for roundtrip calculation
        $t1 = microtime (true);
    
        // Send a zero sized UDP packet towards the destination
        socket_sendto ($send_socket, "", 0, 0, $dest_addr, $port);
    
        // Wait for an event to occur on the socket or timeout after 5 seconds. This will take care of the
        // hanging when no data is received (packet is dropped silently for example)
        $r = array ($recv_socket);
        $w = $e = array ();
        socket_select ($r, $w, $e, 1, 0);
    
        // Nothing to read, which means a timeout has occurred.
        if (count ($r)) {
            // Receive data from socket (and fetch destination address from where this data was found)
            socket_recvfrom ($recv_socket, $buf, 512, 0, $recv_addr, $recv_port);
    
            // Calculate the roundtrip time
            $roundtrip_time = (microtime(true) - $t1) * 1000;
    
            // Print statistics
            //printf ("%3d   %-15s  %.3f ms  %s\n", $ttl, $recv_addr,  $roundtrip_time, '');
            //print_r("\n$recv_addr");
        } else {
            // A timeout has occurred, display a timeout
            //printf ("%3d   (timeout)\n", $ttl);
            return $recv_addr;
        }
    
        // Close sockets
        socket_close ($recv_socket);
        socket_close ($send_socket);
    
        // Increase TTL so we can fetch the next hop
        $ttl++;
    
        // When we have hit our destination, stop the traceroute
        if ($recv_addr == $dest_addr) return $prev_addr;
    }
}
    
//print_r(get_last_hop('207.177.170.166'));
//print_r(get_last_hop('104.254.100.138'));

?>

