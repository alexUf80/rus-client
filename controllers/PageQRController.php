<?php

class PageQRController extends Controller
{
	function fetch()
	{
		return $this->design->fetch('qr.tpl');
	}
}