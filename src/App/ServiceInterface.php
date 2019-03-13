<?php

namespace App;

interface ServiceInterface
{
    public function getId();

    public function getUserId();

    public function getCodename();

    public function getStatus();

    public function getCreateAt();

    public function getEndingAt();
}