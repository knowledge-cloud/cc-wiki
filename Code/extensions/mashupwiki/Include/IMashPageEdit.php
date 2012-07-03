<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 *
 * @author maguibo
 */
interface IMashPageEdit{
    public function createPage($cate,$keyword,$createpage);
    public function configPage($mode,$args);
    public function configFinish();
}
