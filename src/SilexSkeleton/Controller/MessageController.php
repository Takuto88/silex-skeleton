<?php
namespace SilexSkeleton\Controller;

use SilexSkeleton\Service\IMessageService;
use Symfony\Component\HttpFoundation\JsonResponse;
use SilexSkeleton\Exception\EntityNotFoundException;
use SilexSkeleton\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
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
			$response[] = $this->toJsonArray($message);
		}
		return new JsonResponse($response);
	}
	
	public function get($id) {
		$message = $this->messageService->getMessageById($id);
		if($message === null) {
			throw new EntityNotFoundException("exception.msg.entityNotFound");
		}
		
		return new JsonResponse($this->toJsonArray($message));
	}
	
	public function create(Request $request) {
		$text = $request->get("message");
		if(empty($text)) {
			return new JsonResponse(null, 204);
		}
		
		$message = $this->messageService->createMessage($text);
		return new JsonResponse($this->toJsonArray($message), 201);
	}
	
	public function updateMessage($id, Request $request) {
		$text = $request->get("message");
		if(empty($text)) {
			return new JsonResponse(null, 204);
		}
		
		$message = $this->messageService->updateMessage($id, $text);
		return new JsonResponse($this->toJsonArray($message));
	}
	
	public function deleteMessage($id) {
		$this->messageService->deleteMessageById($id);
		return new JsonResponse(null, 204);
	}
	
	private function toJsonArray(Message $message) {
		return array(
				'id' => $message->getId(),
				'message' => $message->getMessage()
		);
	}
	
}