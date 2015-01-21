<?php

namespace Demand\Potato;

class Document extends DocumentObject
{
    /**
     * The unique id of the document.
     * @var string
     */
    private $id;

    /**
     * Contains the CAS value for the document if there is one.
     * @var string
     */
    private $cas = null;

    public function __construct($id, $data, $valueTypes = array())
    {
        $this->id = strval($id);
        parent::__construct($data,$valueTypes);
    }

    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getCas()
    {
        return $this->cas;
    }

    /**
     * @param mixed $cas
     */
    public function setCas($cas)
    {
        $this->cas = $cas;
    }
}