<?php
//存档目录
require_once CONF . '/cache/arctype.cache.php';
$tid   = getgp('tid');
$sql   = "select  distinct iso,audit_type,eid from sp_task_audit_team where tid='$tid' " ;

$wtid  = $db->getAll($sql);
foreach ($wtid as $value) {

    switch ($value['iso']) {
            case 'A01':
                switch ($value['audit_type'])
                {
                    case '1002':
                        $tasks='QMS:一阶段';
                        break;
                    case '1003':
                        $tasks='QMS:二阶段';
                        break;
                    case '1004':
                        $tasks='QMS:监一';
                        break;
                    case '1005':
                        $tasks='QMS:监二';
                        break;  
                }
                break;
            case 'A02':
                switch ($value['audit_type'])
                {
                    case '1002':
                        $tasks='EMS:一阶段';
                        break;
                    case '1003':
                        $tasks='EMS:二阶段';
                        break;
                    case '1004':
                        $tasks='EMS:监一';
                        break;
                    case '1005':
                        $tasks='EMS:监二';
                        break;  
                }
                break;
            case 'A03':
            switch ($value['audit_type'])
            {
                case '1002':
                    $tasks='OHMS:一阶段';
                    break;
                case '1003':
                    $tasks='OHMS:二阶段';
                    break;
                case '1004':
                    $tasks='OHMS:监一';
                    break;
                case '1005':
                    $tasks='OHMS:监二';
                    break;  
            }
            break;

    }
    $first[] =$tasks;

}
    $char = implode(" ", $first);
    $eid=$value['eid'];
    $sql =    "select ep_name from sp_enterprises where eid='$eid' " ;
    $ep_name =$db->get_var($sql);
 //     echo "<pre />";
 // print_r($ep_name);exit;
//审核任务上传文档
$allow_ftype_select = "";
foreach ($arctype_array as $k => $item) {
    if ($k >= 3001 and $k < 4000)
        $allow_ftype_select .= "<option value=\"$k\">$item[name]</option>";
} 
//评分操作
if($_POST['access']){
	load('proj.access')->save_more($_POST['access']);  
	showmsg('success', 'success', "?c=auditor&a=task_edit&tid=$_GET[tid]".'#tab-result');
}
 
if($_GET['access_result_id']){
	 echo 'aaaaaa';
	 $db->update('access_result',array('deleted'=>1),array('id'=>$_GET['access_result_id']));
	 echo $db->sql;
}

$file_arr = array(
    //"SH-001"=>"审核方案策划和审核任务下达书",
    //"SH-002"=>"审核方案策划与实施记录及有效期内体系运行情况评价报告",
    "SH-002-1"=>"QMS审核方案策划与实施记录",
    "SH-002-2"=>"EMS审核方案策划与实施记录",
    "SH-002-3"=>"OHSMS审核方案策划与实施记录",
    "SH-003"=>"管理体系文件审查报告",
    //"SH-004"=>"审核通知书",
    //"SH-005"=>"缴纳认证费用通知单",
    "SH-006"=>"审核员规范声明",
    "SH-007"=>"管理体系审核计划",
    "SH-008"=>"管理体系审核前专业培训记录",
    "SH-009"=>"现场审核检查单",
    "SH-010"=>"首次会议签到表",
    "SH-011"=>"末次会议签到表",
    "SH-012"=>"不符合项报告",
    "SH-013"=>"观察项报告",
    "SH-014"=>"组织认证证书 子证书表达要求说明",
    "SH-015"=>"多场所组织证书附件表达要求说明",
    "SH-016"=>"一阶段审核报告",
    "SH-017"=>"管理体系审核报告",
    "SH-018"=>"末次会议记录",
    "SH-019"=>"认证组织信息确认、变更反馈单",
    "SH-020"=>"获证客户需求及相关意见反馈卡",
    "SH-021"=>"现场审核差旅费报销清单",
    "SH-022"=>"审核员评价表",
    "SH-023"=>"管理体系初审 再认证审核档案清单",
    "SH-024"=>"管理体系监督审核档案清单",  
    "FJ-001"=>"审核一致性要点提示(内部审核员)",  
    "FJ-002"=>"CCAA审核经历记录表",
    "HB000-1"=>"临时场所分布表",
    "HB000-2"=>"多场所分布表",

);
$tid      = getgp('tid');
$ct_id    = getgp('ct_id');
if ($tid) 
{
    //审核文档
    $sql  = "select * from sp_attachments where tid='$tid' ORDER BY `sort`";
    $res  = $db->query($sql);
    $span = array();
    while ($rt = $db->fetch_array($res)) {
        $rt['uid']              = f_username($rt['create_uid']);
        $enterprises_archives[] = $rt;
        $span[$rt['sort']]      = $rt['name'];
    }
	$plan_check=$file_check='';
    $task_info = $db->get_row("select eid,upload_plan_date,upload_file_date,rect_finish,bufuhe from sp_task where id='$tid' ");
	if($task_info['upload_plan_date'] and $task_info['upload_plan_date']!="0000-00-00 00:00:00")
		$plan_check="checked";
	if($task_info['upload_file_date'] and $task_info['upload_file_date']!="0000-00-00 00:00:00")
		$file_check="checked";
	$eid=$task_info['eid'];
    $ep_name   = $db->get_var("select ep_name from sp_enterprises where eid='$eid'");
    //审核计划通知书
    $task_docs = array();
    $query     = $db->query("SELECT id,name FROM sp_attachments WHERE ct_id = '$task_info[ct_id]' AND ftype = '1005'");
    while ($rt = $db->fetch_array($query)) {
        $task_docs[$rt['id']] = $rt;
    }
    //审核项目
    $project = array();
    $query   = $db->query("SELECT scope,id,audit_ver,audit_type,cti_id,use_code FROM sp_project WHERE tid = '$tid'");
    while ($rt = $db->fetch_array($query)) {
        $rt['audit_ver_V']   = f_audit_ver($rt['audit_ver']);
        $rt['audit_type_V']  = f_audit_type($rt['audit_type']);
		//计算合同项目id
		$cti_ids[$rt['cti_id']]=$rt['cti_id'];
        $projects[$rt['id']] = $rt;
    }
	//评分功能 

    $qua_type = $qua_arr = array();
    $qua_arr = $db->getAll("SELECT distinct qua_type from sp_task_audit_team where tid = '$tid'");
    foreach ($qua_arr as $key => $value) {
        if     ('0' == $key)
            $qua_type[0] = $value[qua_type];
        elseif ('1' == $key)
            $qua_type[1] = $value[qua_type];
        elseif ('2' == $key)
            $qua_type[2] = $value[qua_type];
    }

    $iso=$db->getAll("SELECT iso FROM `sp_project` WHERE `ct_id` = '$ct_id' AND `deleted` = '0' group by iso");
    foreach( $iso as $v){
     switch ($v['iso'])
     {
        case 'A01':
            $wjm='SH-002-1';
            break;
        case 'A02':
            $wjm='SH-002-2';
            break;
        case 'A03':
            $wjm='SH-002-3';
            break;
     }
     $str_wjm.= $wjm.'|';
    }
    $str_wjm= substr($str_wjm,0,strlen($str_wjm)-1);

    //当前审核员 当前任务 审核的体系
    $isos  = array();
    $query = $db->query("SELECT iso FROM sp_task_audit_team WHERE tid = '$tid' AND uid = '" . current_user('uid') . "' AND role != ''");
    while ($rt = $db->fetch_array($query)) {
        $isos[] = $rt['iso'];
    }
    //评定问题
    $assess_notes = $db->get_results("select * from sp_assess_notes WHERE 1 AND tid='$tid' AND deleted='0' ORDER BY id");
    //已上传的文档
    //获取合同的体系
    $ct_isos      = array();
    $query      = $db->query("SELECT iso FROM sp_contract_item WHERE ct_id = '$ct_id' AND deleted = 0");
    while ($rt = $db->fetch_array($query)) {
        $ct_isos[] = $rt['iso'];
    }
    $ct_isos   = array_unique($ct_isos);
    $where_arr = array();
    foreach ($ct_isos as $iso) {
        if ('A01' == $iso)
            $where_arr[] = "iso & 1";
        elseif ('A02' == $iso)
            $where_arr[] = "iso & 2";
        elseif ('A03' == $iso)
            $where_arr[] = "iso & 4";
        elseif ('F' == $iso)
            $where_arr[] = "iso & 8";
    }
    unset($iso);
    //审核要求
    $task_note     = $db->get_row("select step2,step3 from sp_task_note where tid='{$tid}' ");
    $task_note1     = $db->get_row("select jh_re_note,jh_sp_note from sp_task where id='{$tid}' ");
	extract($task_note);
	extract($task_note1);
    //获取要上传的文档列表
    $xq_attachs    = array();
    if ($where_arr) {
        $query = $db->query("SELECT id,filename FROM sp_settings_attach WHERE 1 AND (" . implode(' OR ', $where_arr) . ") AND deleted = 0 AND type='audit_task_upload' order by vieworder ASC ");
        while ($rt = $db->fetch_array($query)) {
            $xq_attachs[$rt['id']] = $rt['filename'];
        }
    }
    //判断是组长还是审核员
    $query = $db->query("SELECT role FROM sp_task_audit_team WHERE tid = '$tid' AND uid = '" . current_user('uid') . "' AND role != ''");
    while ($rt = $db->fetch_array($query)) {
        if ($rt[role] == '01')
            $auditor_role = 1;
    }
    if (current_user("uid") == '1')
        $auditor_role = 1;
    !$auditor_role && $_where = " AND name='" . current_user("name") . "'";
    $report = $db->get_results("SELECT * FROM `sp_auditor_report` WHERE `tid` = '$tid' AND `deleted` = '0' $_where order by name");
    if ($rid = getgp("rid")) {
        $r_row = $db->get_row("SELECT * FROM `sp_auditor_report` WHERE id=$rid");
        extract($r_row, EXTR_SKIP);
        unset($r_row);
    }
    $form_file = $a_link = $a_link_2017 = "";
    foreach ($file_arr as $k => $val) 
    {
        $form_file .= '<tr><td width="300">';
        $form_file .= $k . " " . $val;
        $form_file .= "</td><td>";
        $form_file .= '<input type="hidden" name="sort[]" value="' . $k . '"/><input type="file" name="archive[]" />';
        $form_file .= "</td><td><span>";

        if ($span[$k])
            $form_file .= $span[$k] . "(已上传)";
        else
            $form_file .= "无";
        $form_file .= "</span><br/></td></tr>";
        $a_link .= "<li>
                      <input type='checkbox' name='docdown' value=a:".$k."|ct_id:".$ct_id."|tid:".$tid.">
                      <a href='?c=doc&a=$k&ct_id=$ct_id&tid=$tid'>$k $val</a>
                    </li>";
    }
    tpl('auditor/task_edit');
}
?>
<tr>
    
</tr>