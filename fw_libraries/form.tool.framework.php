<?php 
/**
 * Simple make form
 * @name formCreator
 * @version 2015.108
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
     * @example Usable parameter type: string $type (HTML5 types)
     * @example Usable parameter type: string $id (unique identification)
     * @example Usable parameter type: string $name (unique name)
     * @example Usable parameter type: string $value (sending value)
     * @example Usable parameter type: string $class (classes)
     * @example Usable parameter type: string $param (other parameters)
     * 
     * @param string $tag (tag name)
     * @param array $type (content type)
     * @param array $params (html parameter in attribute)
     * 
     * @return bool (if notype)
     * @return array (if generating process is ok)
     */
    public function newInputLine($tag='input',$type='text',$params=array('id'=>'default'),$otherContent=null)
    {
        if($type==null)
        {
        	return false;
        }
        else 
        {
            $updateClass=array();
            $updateParams=array();
            foreach($params as $key=>$val)
            {
            	if($key=='class')
            	{
            		$updateClass['class']['open']="";
            		foreach($params['class'] as $val)
            		{
            			$updateClass['class'][]=$val;
            		}
            		$updateClass['class']['close']="'";
            	}
            	else
            	{
            		$updateParams[$key]=" $key='".$val."'";
            	}
            }
            
            if(!empty($params['label']))
            {
                $updateParams['label']="<label id='".$params['id']."_label' for='".$params['id']."'>".$params['label']."</label>";
            }
            $this->form['label'][$params['id']]=$updateParams['label'];
            $this->form[$tag][$params['id']]="<$tag class='".implode(" ",$updateClass['class'])."' ".implode(" ",$updateParams).">";
            if($otherContent!=null)
            {
            	$this->form[$tag."_data"][$params['id']]="$otherContent</$tag>";
            }
        	return $this->form;
        }
    }
    
    /**
     * Create form
     * 
     * @param array $id (identification in the system)
     * 
     * @return string (if exist $id retruning html input tag)
     * @todo add div generator for label and input groupe
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
    
    /**
     * Create selectbox from table
     * 
     * @param string $system
     * @param string $group
     * @param string $order ['asc'] | ['desc']
     * @param string $table (your database uniData table name)
     * 
     * @return array
     */
    final private function selectbox($system,$group,$order='asc',$table='formData')
    {
    	if(database::json===0)
    	{
    		$mysql = new mysqlInterface;
    		$mysql->config();
    		$mysql->connect();
    		$mysql->select(array($table=>array('condition'=>array('`par_system`='.$system,'`par_group`='.$group),'sort'=>array($order=>'`id`'))));
    		$data = $mysql->execute();
    			
    		foreach($data as $key=>$val)
    		{
    			$default = (($val['par_default']!='')?' selected':'');
    			$arr[] = "<option value='{$val['par_value']}'$default>{$val['par_name']}</option>";
    		}
    			
    		return implode(PHP_EOL,$arr);
    	}
    }
}
?>