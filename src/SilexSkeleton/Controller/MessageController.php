<?php
namespace SilexSkeleton\Controller;

use SilexSkeleton\Service\IMessageService;
use Symfony\Component\HttpFoundation\JsonResponse;
class MessageController {
	
	private $messageService;
	
	public function __construct(IMessageService $messageService){
		$this->messageService = $messageService;
		$messageService->createExampleMessages();
	}
	
	public function index() {
		$messages = $this->messageService->getAllMessages();
		$response = array();
		foreach($messages as $message) {
			$response[] = array(
					'id' => $message->getId(),
					'message' => $message->getMessage()
			);
		}
		return new JsonResponse($response);
	}
	
}