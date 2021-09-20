<?php

namespace Statik;

class Statik
{
	
	protected $directory_filter = array( '.', '..' );

	public function generateHTML( $source_path, $target_path, $template = false ) {

		var_dump( $this->get_files( $source_path ) );

		return 'DONE!';
		
	}

	protected function get_files( string $path ) {

		$source_paths = $source_entries = array();

		$out = array( 'all_paths' => array(), 'files' => array() );

		$dir = dir( $path );

		while ( false !== ( $entry = $dir->read() ) ) {

			if ( !in_array( $entry, $this->directory_filter ) ) {

				$path = $dir->path . '/' . $entry;

				if ( is_dir( $path ) ) {

					$out = array_merge( $out, $this->get_files( $path ) );

				} else {
			
					$out['files'][] = $path;

				}

				$out['all_paths'][] = $path;

			}
		
		}
		
		$dir->close();

		return $out;

	}

}
