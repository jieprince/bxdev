取得电子保单接口说明文档
1.平安保险
	a.下载电子保单
	String com.yanda.imsp.util.ins.PingAnPolicyFetcher.applyElectricPolicyBill(String policyNo, String policyValidateNo, String orderNum, String isSeperated, String path, String isDevS)
	
			     * 传入目录temp目录结构：
				 * 	temp
				 * 	  |--config
				 * 		|---EXV_BIS_IFRONT_PCIS_ZTXH_001_PRD.pfx   (证书文件）
				 * 	  |--message
				 * 		|---10118011900113650427_er.html           (错误日志文件）
				 * 		|---10118011900113650427_pl.pdf            (成功返回的pdf保单)
				 * </pre>	
				 * @param policyNo   保单号
				 * @param policyValidateNo  验证码
				 * @param orderNum  订单编号
				 * @param isSeperated  取值："single"/"group"/""    如果是个单，填写空；如果是团单团打，填写group；如果是团单个单，填写single
				 * @param path  文件路径
				 * @param isDevS  Y/N     Y表示调试，输出测试信息    N表示不输出测试信息 
				 * @return String 请求返回日志或pdf保单文件路径
				 
2.太平洋保险
	a.投保或下载电子保单接口 （投保与下载电子保单接口相同，报文不同而已）
	String com.yanda.imsp.util.ins.CPICPolicyFecher.applyPolicy(String xmlContent) throws Exception
	参数是传入相应报文，返回字符串是请求保险公司接口后返回的报文
	
	
3.华安保险
	a.承保接口
	String com.yanda.imsp.util.ins.SinosafePolicyFecher.applyPolicy(String xmlContent, String logFile)
	参数说明：
		xmlContent		报文内容
		logFile			日志文件全路径，如：M:/meanway/workspace/PDFFetcher/log/2014-06-23_201751_logInfo.log
		
	b.查询保单信息接口
	String com.yanda.imsp.util.ins.SinosafePolicyFecher.queryPolicyByMessage(String xmlContent, String logFile)
	参数说明：
		xmlContent		报文内容
		logFile			日志文件全路径，如：M:/meanway/workspace/PDFFetcher/log/2014-06-23_201751_logInfo.log
	