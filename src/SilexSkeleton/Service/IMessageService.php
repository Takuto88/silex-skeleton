<?php
namespace SilexSkeleton\Service;

use SilexSkeleton\Entity\Message;
use SilexSkeleton\Exception\EntityNotFoundException;
/**
 * Interface for a basic message service
 * @author Lennart Rosam - <hello@takuto.de>
 */
interface IMessageService {
	
	/**
	 * Returns all Message objects from the database
	 * 
	 * @return Message[] An array of messages
	 */
	public function getAllMessages();
	
	/**
	 * Returns a Message by ID
	 * 
	 * @param integer $id
	 * @return Message|NULL The message or null
	 */
	public function getMessageById($id);
	
	/**
	 * Creates a new message
	 * 
	 * @param string $message
	 * @return Message The message object containing the ID
	 */
	public function createMessage($message = "");
	
	/**
	 * Updates the given message
	 * 
	 * @param integer $id The ID of the message to update
	 * @param string $string The message string
	 * 
	 * @throws EntityNotFoundException When the ID does not exist
	 * @return Message The updated message
	 */
	public function updateMessage($id, $string = "");
	
	/**
	 * Deletes the given message by id
	 * 
	 * @param integer $id
	 * @throws EntityNotFoundException When the ID does not exist
	 * @return void
	 */
	public function deleteMessageById($id);
	
	/**
	 * Creates example messages in the Database if there are no 
	 * messages yet.
	 * 
	 * @return void
	 */
	public function createExampleMessages();
	
}