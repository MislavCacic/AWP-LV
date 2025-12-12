<?php
require_once "iRadovi.php";

class DiplomskiRadovi implements iRadovi {
    public $nazivRada;
    public $tekstRada;
    public $linkRada;
    public $oibTvrtke;

    private static $items = []; // lista svih objekata

    public function create($naziv, $tekst, $link, $oib): void {
        $this->nazivRada = $naziv;
        $this->tekstRada = $tekst;
        $this->linkRada  = $link;
        $this->oibTvrtke = $oib;

        self::$items[] = $this;
    }

    public function save($conn): void {
        $sql = "INSERT IGNORE INTO diplomski_radovi
                (nazivRada, tekstRada, linkRada, oibTvrtke)
                VALUES (?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);

        foreach (self::$items as $item) {
            $stmt->bind_param(
                "ssss",
                $item->nazivRada,
                $item->tekstRada,
                $item->linkRada,
                $item->oibTvrtke
            );
            $stmt->execute();
        }
        $stmt->close();
    }

    public function read($conn) {
        $sql = "SELECT * FROM diplomski_radovi";
        
        return $conn->query($sql);
    }
}
?>