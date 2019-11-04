<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

final Class Master {


    function get_tahun($nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		$now = date('Y');
		for ($i=$now-2; $i < $now+2 ; $i++) { 
			$year[] = $i;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row?'selected':'';
					$field.='<option value="'.$row.'" '.$sel.' >'.strtoupper($row).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function custom_selection_radio($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}

		$field='';

		$field.='<div class="checkbox">';
		foreach($data as $row){
			$sel = $nid==$row[$custom['id']]?'checked':'';
			$field.='<label>';
			$field.='<input type="checkbox" name="'.$name.'" class="ace" value="'.$row[$custom['id']].'" '.$sel.'>';
			$field.='<span class="lbl"> '.$row[$custom['name']].' </span>';
			$field.='</label>';
		}	
		$field.='</div>';
			
		
		return $field;
		
    }
    
    function get_bulan($nid='',$name,$id,$class='',$required='',$inline='') {
		//print_r($nid);die;
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$year = array();
		for ($i=1; $i < 13 ; $i++) { 
			$list = array(
				'key' => $i,
				'value' => $CI->tanggal->getBulan($i),
				);
			$year[] = $list;
		}
		$data = $year;

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.=$fieldset.'
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="0" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row['key']?'selected':'';
					$field.='<option value="'.$row['key'].'" '.$sel.' >'.strtoupper($row['value']).'</option>';
				}	
			
		$field.='
		</select>
		'.$fieldsetend;
		
		return $field;
		
    }

    function custom_selection($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if(isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$data = $db->get($custom['table'])->result_array();

		}else if(isset($custom['where'])&&isset($custom['where_in'])){
			$db->where_in($custom['where_in']['col'],$custom['where_in']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else if(isset($custom['like'])&&isset($custom['where'])){
			$db->like($custom['like']['col'],$custom['like']['val']);
			$db->where($custom['where']);
			$data = $db->get($custom['table'])->result_array();
		}else{
			$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		}
        //$data = $db->where($custom['where'])->get($custom['table'])->result_array();
		
		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function custom_selection_with_join($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		foreach ($custom['join'] as $k => $v) {
			$db->join($v['relation_table'],$custom['table'].'.'.$v['relation_ref_id'].'='.$v['relation_table'].'.'.$v['relation_id'],$v['join_type']);
		}
        $db->where($custom['where']);
		$data = $db->get($custom['table'])->result_array();

		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }


    function on_change_custom_selection($custom=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		
		if($nid != ''){
        	$data = $db->where($custom['id'], $nid)
        			   ->where($custom['where'])
        			   ->get($custom['table'])->result_array();
		}else{
			$data = array();
		}
		
		$selected = $nid?'':'selected';
		$readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
		
		$starsign = $required?'*':'';
		
		$fieldset = $inline?'':'<fieldset>';
		$fieldsetend = $inline?'':'</fieldset>';
		
		$field='';
		$field.='
		<select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
			<option value="" '.$selected.'> - Silahkan pilih - </option>';

				foreach($data as $row){
					$sel = $nid==$row[$custom['id']]?'selected':'';
					$field.='<option value="'.$row[$custom['id']].'" '.$sel.' >'.strtoupper($row[$custom['name']]).'</option>';
				}	
			
		$field.='
		</select>
		';
		
		return $field;
		
    }

    function get_change($params=array(), $nid='',$name,$id,$class='',$required='',$inline='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        if($nid!=''){
            $data = $db->where($params['id'], $nid)->get($params['table'])->result_array();
        }else{
            $data = array();
        }

        $selected = $nid?'':'selected';
        $readonly = '';//$CI->session->userdata('nrole')=='approver'?'readonly':'';
        
        $starsign = $required?'*':'';
        
        $fieldset = $inline?'':'<fieldset>';
        $fieldsetend = $inline?'':'</fieldset>';
        
        $field='';
        $field.=$fieldset.'
        <select class="'.$class.'" name="'.$name.'" id="'.$id.'" '.$readonly.' '.$required.' >
            <option value="0" '.$selected.'> - Silahkan pilih - </option>';
                foreach($data as $row){
                    $sel = $nid==$row[$params['id']]?'selected':'';
                    $field.='<option value="'.$row[$params['id']].'" '.$sel.' >'.strtoupper($row[$params['name']]).'</option>';
                }
                
            
        $field.='
        </select>
        '.$fieldsetend;
        return $field;
        
    }
    
    function get_content_dashboard() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);

		/*total surat masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1,2) ) ){
			$db->where('surat_flag', 1);
		}else{
			$db->where(array('surat_flag'=>1,'surat_tujuan_pg_id'=>$CI->session->userdata('user')->role_id));
		}
		$db->from('surat');
		$db->where('is_active', 'Y');
		$surat_masuk = $db->get(); 
		$data['total_surat_masuk'] = $surat_masuk->num_rows();

		/*total surat masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1,2) ) ){
			$db->where('surat_flag', 2);
		}else{
			$db->where(array('surat_flag'=>2,'surat_tujuan_pg_id'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('surat');
		$db->where('is_active', 'Y');
		$surat_keluar = $db->get(); 
		$data['total_surat_keluar'] = $surat_keluar->num_rows();

		/*total disposisi masuk*/
		if( in_array($CI->session->userdata('user')->role_id, array(1) ) ){
			$db->where('dt_id is not null');
		}else{
			$db->where(array('dt_tujuan_pg_id'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('disposisi_tujuan');
		$disposisi = $db->get();
		$data['total_disposisi_masuk'] = $disposisi->num_rows();

		/*total disposisi keluar*/
		if( in_array($CI->session->userdata('user')->role_id, array(1) ) ){
			$db->where('disposisi_id is not null');
		}else{
			$db->where(array('created_by'=>$CI->session->userdata('user')->pg_id));
		}
		$db->from('disposisi');
		$db->where('is_active', 'Y');
		$disposisi_keluar = $db->get(); 
		$data['total_disposisi_keluar'] = $disposisi_keluar->num_rows();


		return $data;
		
    }

    function get_graph_data() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qry = 'SELECT web_modul.`wm_name`, COUNT(web_posting.wm_id)AS total
				FROM web_posting
				LEFT JOIN web_modul ON web_modul.`wm_id`=web_posting.`wm_id`
				GROUP BY web_posting.`wm_id`';
		$sql = $db->query($qry)->result();
		
		return $sql;
		
    }

    function get_graph_polling() {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qry = 'SELECT wpl_question, (SELECT GROUP_CONCAT(CONCAT(wpl_option),"=",counter)AS hasil
				FROM web_polling_answer
				WHERE wpl_id=web_polling.`wpl_id`) AS hasil
				FROM web_polling
				WHERE is_active="Y"
				ORDER BY wpl_tanggal DESC
				LIMIT 1';
		$sql = $db->query($qry)->row();
		
		return $sql;
		
    }

    function get_custom_data($table, $select, $where, $return) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select($select);
		$db->from($table);
		$db->where($where);
		$qry = $db->get()->$return();
		return $qry;
		
    }

    function get_max_number($table, $field) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select_max($field);
		$db->from($table);
		$qry = $db->get()->row();
		/*plus 1*/
		$max_num = $qry->$field + 1 ;
		return $max_num;
		
    }

    function get_no_antrian_poli($kode_bagian, $kode_dokter) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select_max('no_antrian');
		$db->from('pl_tc_poli');
		$db->where( "kode_bagian='".$kode_bagian."' and kode_dokter=".$kode_dokter." and YEAR(tgl_jam_poli)=".date('Y')." and MONTH(tgl_jam_poli)=".date('m')." and DAY(tgl_jam_poli)=".date('d')."" );
		$qry = $db->get()->row();
		/*plus 1*/
		$max_num = $qry->no_antrian + 1 ;
		return $max_num;
		
	}
	
	function get_no_antrian_pm($kode_bagian) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$db->select_max('no_antrian');
		$db->from('pm_tc_penunjang');
		$db->where( "kode_bagian='".$kode_bagian."' and YEAR(tgl_daftar)=".date('Y')." and MONTH(tgl_daftar)=".date('m')." and DAY(tgl_daftar)=".date('d')."" );
		$qry = $db->get()->row();
		/*plus 1*/
		$max_num = $qry->no_antrian + 1 ;
		return $max_num;
		
    }

    function get_qr_code($data) {
		
		$CI =&get_instance();
		$db = $CI->load->database('default', TRUE);
		$qr_code = $data->regon_booking_kode.'-'.$data->regon_booking_no_mr.''.$data->regon_booking_klinik.''.$data->regon_booking_kode_dokter.''.$data->regon_booking_instalasi;

		return $qr_code;
		
		}
		
		function thousandsCurrencyFormat($num) {

			if($num>1000) {
		
						$x = round($num);
						$x_number_format = number_format($x);
						$x_array = explode(',', $x_number_format);
						$x_parts = array('k', 'm', 'b', 't');
						$x_count_parts = count($x_array) - 1;
						$x_display = $x;
						$x_display = $x_array[0] . ((int) $x_array[1][0] !== 0 ? '.' . $x_array[1][0] : '');
						$x_display .= $x_parts[$x_count_parts - 1];
		
						return $x_display;
		
			}
		
			return $num;
		}
    
	
}

?>