<?php 
/**
 * Simple make form
 * @name formCreator
 * @version 2015.002
 * @category Sencillo Library
 * @see http://www.opensencillo.com
 * @author Bc. Peter Horváth
 * @license Distributed under the General Public License (GPL) http://www.gnu.org/licenses/gpl-3.0.html This program is distributed in the hope that it will be useful - WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * @todo Bad comment on method getById() and groupToLines()
 */
class formCreator
{
    protected $form=array();
    
    /**
     * Create input
     * 
     * @example Usable parameter type: sstring $type (HTML5 types)
     * @example Usable parameter type: string $id (unique identification)
     * @example Usable parameter type: string $name (unique name)
     * @example Usable parameter type: string $value (sending value)
     * @example Usable parameter type: string $class (classes)
     * @example Usable parameter type: string $param (other parameters)
     * 
     * @param array $type (content type)
     * @param array $params (html parameter in attribute)
     * 
     * @return bool (if notype)
     * @return array (if generating process is ok)
     */
    public function create($type,$params)
    {
        if($type==null)
        {
        	return false;
        }
        else 
        {
            $params=array();
            $updateParams=array();
            if($params['value']!=null)
            {
            	$updateParams['value']=" value='".$params['value']."'";
            }
            if($params['id']!=null)
            {
                $updateParams['id']=" id='".$params['id']."'";
            }
            if($params['name']!=null)
            {
                $updateParams['name']=" name='".$params['name']."'";
            }
            if($params['class']!=null)
            {
                $updateParams['class']=" class='".$params['class']."'";
            }
            if($params['param']!=null)
            {
                $updateParams['param']=" param='".$params['param']."'";
            }
            if($params['label']!=null)
            {
                $updateParams['label']="<label id='".$params['id']."_label' for='".$params['id']."'>".$params['label']."</label>";
            }
            $this->form['label'][$params['id']]=$updateParams['label'];
            $this->form['input'][$params['id']]="<input type='$type'".$params['value'].$params['id'].$params['name'].$params['class'].$params['param'].">";
        	return array($this->form['label'][$params['id']],$this->form['input'][$params['id']]);
        }
    }
    
    /**
     * Create form
     * 
     * @param array $id (identification in the system)
     * 
     * @return string (if exist $id retruning html input tag)
     */
    public function getById($id,$action="/",$method="post")
    {
    	$out='';
    	
    	foreach($id as &$value)
    	{
    		$out.=$value;
    	}
    	return '<form action="'.$action.'" method="'.$method.'">'.$out.'</form>';
    }
    
    /**
     * Create a new form line (no save)
     * 
     * @param string $id
     * @param string $tag=null
     * 
     * @return string
     */
    public function groupToLines($id,$tag=null,$params=null)
    {
        $out='';
        $i=0;
        foreach($id as &$id_value)
        {
            $j=0;
            $tag=str_ireplace(array('<','>'),'',$tag);
            if($tag!=null)
            {
                $out.="<".$tag." ".$params[$i][$j++].">".$this->form['label'][$id_value]."</".$tag."><".$tag." ".$params[$i][$j++].">".$this->form['input'][$id_value]."</".$tag.">";
            }
            else 
            {
                $tag="div";
                $out.="<".$tag." ".$params[$i][$j++].">".$this->form['label'][$id_value].$this->form['input'][$id_value]."</".$tag.">";
            }
            $i++;
        }
        return $out;
    }
}
?>