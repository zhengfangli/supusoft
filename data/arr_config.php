<?php

	$arr_hr_sex = array(1=>'男',2=>'女');//性别

	$arr_hr_technical=array('00'=>'助理工程师','01'=>'工程师','02'=>'高级工程师');//职称 认证人员专业能力评定表 app/doc_tpl/RZ-001.PHP
	
	$arr_hr_department=array('00'=>'大学专科','01'=>'大学本科','02'=>'硕士学位及以上','03'=>'大学专科以下学历');//学历 认证人员专业能力评定表 app/doc_tpl/RZ-001.PHP

	$arr_hr_zige = array(101=>'已年度确认',102=>'未年度确认',201=>'已再认证',202=>'未再认证',301=>'不在有效期');

	$arr_audit_iso  = array('A01'=>'QMS','A02'=>'EMS','A03'=>'OHSMS','A0102'=>'50430');//审核类型
	$arr_iso_name   = array('A01'=>'质量管理体系','A02'=>'环境管理体系','A03'=>'职业健康安全管理体系');//审核类型
	$arr_iso_name_e = array('A01'=>'Quality','A02'=>'Environmental','A03'=>'Occupational Health and Safety');

	$arr_audit_type = array('1001'=>'初审','1002'=>'一阶段','1003'=>'二阶段','1004'=>'监一','1005'=>'监二','1006'=>'监三','1007'=>'再认证','1008'=>'专项审核','1009'=>'特殊监督','1101'=>'变更','99'=>'其他');

	$arr_qua_type  = array('01'=>'高级审核员','02'=>'审核员','03'=>'实习审核员','04'=>'技术专家','05'=>'高级审查员','06'=>'审查员','07'=>'主任审核员','99'=>'其他','1000'=>'认证评定','1001'=>'验证');//资格
	
	$arr_audit_job = array(0=>'兼职',1=>'专职');//专职兼职

	$arr_addr_prod_check = array(1=>'生产地址',2=>'服务地址',3=>'运营地址',);

	$arr_noticeStype  = array(1=>'公司公告',2=>'审核员公告',3=>'客户公告',11=>'个人通知');

	//风险级别
	$arr_risk_level = array('01'=>'高','02'=>'中','03'=>'低');

	/**合作方**/
		//客户级别
		$arr_level = array('1'=>'优质', '2'=>'一半', '3'=>'劣质');

		//派组方式
		$arr_paizu = array('1'=>'机构派组', '2'=>'合作方派组', '3'=>'混合派组');
	/**合作方**/