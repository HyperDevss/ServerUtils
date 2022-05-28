<?php

namespace serverutils;

class OflineMessageHandler {
    
    private $server;
    
    public function __construct(ServerUtils $server) {
        $this->server = $server;
    }
    
    public function handle($buffer) {
        
    }
}