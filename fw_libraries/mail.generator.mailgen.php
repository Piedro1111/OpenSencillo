<?php
class mailGen
{
	protected $head;
	protected $body;
	protected $mail;
	protected $smtp;
	protected $text;
	protected $from;
	protected $to;
	protected $charset='UTF-8';
	protected $ctype='text/html';
	protected $subiect;
	
	/**
	 * Set mail recipient
	 * @param string
	 */
	public function to($to)
	{
		$this->to = $to;
	}
	
	/**
	 * Set mail sender
	 * @param string
	 */
	public function from($from)
	{
		$this->from = $from;
	}
	
	/**
	 * Set subiect
	 * @param subiect
	 */
	public function subiect($subiect)
	{
		$this->subiect = $subiect;
	}
	
	/**
	 * Header
	 */
	private function head()
	{
		$this->head[]='MIME-Version: 1.0';
		$this->head[]='Content-type: '.$this->ctype.'; charset='.$this->charset;
		$this->head[]='From: '.$this->from;
		$this->head[]='Reply-To: '.$this->from;
		$this->head[]='X-Mailer: PHP/' . phpversion();
		return implode("\r\n",$this->head);
	}
	
	/**
	 * Message content
	 * @param string $content only one line 
	 */
	public function body($content)
	{
		$this->body[] = $content;
	}
	
	/**
	 * Send mail
	 * @return boolean
	 */
	public function send()
	{
		$this->head();
		if((filter_var($this->from, FILTER_VALIDATE_EMAIL))&&(filter_var($this->to, FILTER_VALIDATE_EMAIL)))
		{
			return mail(''.$this->to.'',$this->subiect,implode(PHP_EOL,$this->body),implode("\r\n",$this->head));
		}
		else
		{
			return false;
		}
	}
}
?>