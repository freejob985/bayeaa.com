<?php


namespace App\Observers;

use App\Helpers\Files\Storage\StorageDisk;
use App\Models\Message;

class MessageObserver
{
	/**
	 * Listen to the Entry deleting event.
	 *
	 * @param Message $message
	 * @return void
	 */
	public function deleting(Message $message)
	{
		// Storage Disk Init.
		$disk = StorageDisk::getDisk();
		
		// Delete the message's file
		if (!empty($message->filename)) {
			if ($disk->exists($message->filename)) {
				$disk->delete($message->filename);
			}
		}
		
		// If it is a Conversation, Delete it and its Messages if exist
		if ($message->parent_id == 0) {
			$conversationMessages = Message::where('parent_id', $message->id);
			if ($conversationMessages->count() > 0) {
				foreach ($conversationMessages->cursor() as $conversationMessage) {
					$conversationMessage->delete();
				}
			}
		}
	}
}
