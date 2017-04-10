<?php
/**
 * Send and construct mail
 * @name mailgen
 * @version 2017.104
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/copyleft/gpl.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
class mailGen
{
	protected $head;
	protected $body;
	protected $mail;
	protected $from;
	protected $to;
	protected $charset='UTF-8';
	protected $ctype='text/html';
	protected $subject;
	
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
	 * Set subject
	 * @param subject
	 */
	public function subject($subject)
	{
		$this->subject = $subject;
	}
	
	/**
	 * Use html mimetype
	 * @param boolean
	 */
	public function html($bool=true)
	{
		if($bool)
		{
			$this->ctype = 'text/html';
		}
		else 
		{
			$this->ctype = 'text/plain';
		}
	}
	
	/**
	 * Use text mimetype
	 * @param boolean
	 */
	public function text($bool=true)
	{
		if($bool)
		{
			$this->ctype = 'text/plain';
		}
		else
		{
			$this->ctype = 'text/html';
		}
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
		$this->head[]='X-Mailer: PHP/'.phpversion();
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
			return mail(''.$this->to.'',$this->subject,implode(PHP_EOL,$this->body),implode("\r\n",$this->head));
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * Compile mail but not send mail
	 * @return mixed multiple array
	 */
	public function compile()
	{
		$h = $this->head();
		$b = implode(PHP_EOL,$this->body);
		return array(
				'source'=>array(
						'head'=>$this->head,
						'from'=>$this->from,
						'to'=>$this->to,
						'subject'=>$this->subject,
						'body'=>$this->body,
						'text'=>convert::stripHtml($this->body)
				),
				'send'=>array(
						'head'=>$h,
						'to'=>$this->to,
						'subject'=>$this->subject,
						'body'=>$b
				)
		);
	}
}
?>