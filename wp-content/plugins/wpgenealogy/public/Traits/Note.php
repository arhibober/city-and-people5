<?php

namespace WPGenealogy_Public\Traits;

trait Note {

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function notes_get() {

		/**
		 * get all notes.
		 *
		 */
		$this->get(\WPGenealogy\Models\Note::class);
	}

	/**
	 * Function to get trees.
	 *
	 * @since    3.0.0
	 */
	public function notes_get_alt() {

		/**
		 * get all notes.
		 *
		 */

		$data = $this->post_fixer($_POST);

		$this->get_alt(\WPGenealogy\Models\Note::class, $data);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function note_add() {

		$data = $this->post_fixer($_POST);

		
		// Define array to store response data.
		$result = array();
		
		// Create a family from posted data.
		$note = $this->add(\WPGenealogy\Models\Note::class , $data, true);

		$data['noteID'] = $note->note->id;
		
		// Create a new child for this family.
		$note_link = $this->add(\WPGenealogy\Models\NoteLink::class , $data, true);
		
		$this->update(\WPGenealogy\Models\Note::class , $data, array(
			'id' => $data['noteID']
		) , true);

		$note = \WPGenealogy\Models\Note::find($data['noteID']);

		// Store family data to response array.
		array_push($result, array('note' => $note));

		// Push children data to response array.
		array_push($result, $note_link);

		// response data
		$this->send_json($note);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function note_update() {
		
		/**
		 * note update.
		 *
		 */

		$data = $this->post_fixer($_POST);

		$this->update(\WPGenealogy\Models\Note::class , $data['note'], array(
			'id' => $data['noteID']
		) , false);
	}

	/**
	 * Function to add event.
	 *
	 * @since    3.0.0
	 */
	public function note_delete() {

		/**
		 * note delete.
		 *
		 */

		$data = $this->post_fixer($_POST);

		$this->delete(\WPGenealogy\Models\Note::class , $data['selected']);
	}
}

