<?php
/**
 * Core static interface
 */
interface coreInterface
{
	public function __construct();
	public function version_info();
	public function product();
	public function payLock();
	public function __destruct();
}
?>