<?php
/**
 * Abstract Service Class
 * @id : $Id: AbstractService.php 5531 2010-04-05 07:49:11Z crimson $
 * @version : $Revision: 5531 $
 * @author : $Author: crimson $
 *
 */
abstract class AbstractService{
	 
	/**
	 * Execute Service
	 * @param ParameterSet $webMessageDTO
	 */
	public function service($webMessageDTO){
		// ��û �޽��� �����ϱ�
		$requestBytes = $this->createMessage($webMessageDTO);
		
		if(LogMode::isAppLogable()){
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("�۽� ".strlen($requestBytes)." Bytes");
		}
		
		// ��û �޽��� ������
		$responseBytes = $this->send($requestBytes);
		
		if(LogMode::isAppLogable()){
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("���� ".strlen($responseBytes)." Bytes");
		}
		
		// ���� �� �޽��� �Ľ��ϱ�
		$responseDTO = $this->parseMessage($responseBytes);
		
		if(LogMode::isAppLogable()){
			$logJournal = NicePayLogJournal::getInstance();
			$logJournal->writeAppLog("��� -> [".$responseDTO->getParameter("ResultCode")."][".trim($responseDTO->getParameter("ResultMsg"))."]");
		}
		
		return $responseDTO;
		
	}
	
	/**
	 * Create a ByteMessage
	 * @param ParameterSet $webMessageDTO
	 */
	public abstract function createMessage($webMessageDTO);
	
	/**
	 * Send to m&Bank Interface System
	 * @param ParameterSet $webMessageDTO
	 */
	public abstract function send($webMessageDTO);
	
	/**
	 * Receive Bytes Message from m&Bank Interface System. 
	 * Parsing a ByteMessage, Transform Bytes to ParameterSet 
	 * @param ParameterSet $responseBytes
	 */
	public abstract function parseMessage($responseBytes);
	
}
?>