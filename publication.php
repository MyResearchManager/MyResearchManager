<?php

class Publication
{
    public $idPublication = -1;
    public $title   = "";
    public $doi     = "";
    public $year    = "";
    public $authors = array("");

    public function bibtex()
    {
       return "@MISC{Key$year,".bibtex_body()."}";
    }

    public function bibtex_body()
    {
       $body = "author = {";
       foreach ($authors as $author) 
          $body = $body."$author,";
       $body = $body."},";

       $body = $body."title = { $title },";
       $body = $body."doi = { $doi },";
       $body = $body."year = { $year },";

       return $body;
    }
}


class JournalPublication extends Publication
{
    public $idJournalPublication = -1;
    public $journal = "";

    public function bibtex()
    {
       return "@ARTICLE{Key$year,".bibtex_body()."}";
    }

    public function bibtex_body()
    {
       $body = parent::bibtex_body();

       $body = $body."journal = { $journal },";

       return $body;
    }
}


class ConferencePublication extends Publication
{
    public $idConferencePublication = -1;
    public $conference = "";
    public $date       = "";
    public $location   = "";

    public function bibtex()
    {
       return "@INPROCEEDINGS{Key$year,".bibtex_body()."}";
    }

    public function bibtex_body()
    {
       $body = parent::bibtex_body();

       $body = $body."conference = { $conference },";
       $body = $body."date = { $date },";
       $body = $body."location = { $location },";

       return $body;
    }
}

?>
