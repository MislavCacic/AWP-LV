<?php

interface iRadovi {
    public function create($naziv, $tekst, $link, $oib);
    public function save($conn);
    public function read($conn);
}

?>