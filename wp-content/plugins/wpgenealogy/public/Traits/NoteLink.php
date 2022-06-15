<?php 
namespace WPGenealogy_Public\Traits;

trait NoteLink {

	/**
	 * function to get note links.
	 *
	 * @since    3.0.0
	 */
	public function note_links_get() {
		
		/**
		 * get all note_link.
		 *
		 */
		$this->get(\WPGenealogy\Models\NoteLink::class);
	}

	/**
	 * function to get note links.
	 *
	 * @since    3.0.0
	 */
	public function note_links_get_alt() {
		$data = $this->post_fixer($_POST);
		
		$note_links = $this->get_alt(\WPGenealogy\Models\NoteLink::class, $data, true);

		$note_links->getCollection()->transform(function ($note_link) {
			$note_link->person = \WPGenealogy\Models\People::where('personID', $note_link->persfamID)->where('gedcom', $note_link->gedcom)->first();
			$note_link->family = \WPGenealogy\Models\Family::where('familyID', $note_link->persfamID)->where('gedcom', $note_link->gedcom)->first();
			return $note_link;
		});

		wp_send_json($note_links, 200);
	}

	/**
	 * function to get note link.
	 *
	 * @since    3.0.0
	 */
	public function note_link_get_alt() {
		/**
		 * get note link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->single(\WPGenealogy\Models\NoteLink::class, $data);
	}

	/**
	 * function to update note link.
	 *
	 * @since    3.0.0
	 */
	public function note_link_update() {

		/**
		 * update note link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->update(\WPGenealogy\Models\Note::class, $data['note'], ['id' => $data['note']['id']]);
	}

	/**
	 * function to delete note link.
	 *
	 * @since    3.0.0
	 */
	public function note_link_delete() {

		/**
		 * delete note link.
		 *
		 */
		$data = $this->post_fixer($_POST);
		
		$this->delete(\WPGenealogy\Models\NoteLink::class, $data['selected']);
	}
}