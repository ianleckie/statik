<?php

namespace Statik;

class Statik
{
	
	public function generateHTML( $source_path, $target_path, $template = false ) {

		var_dump( $this->get_files( $source_path ) );

		return 'DONE!';
		
	}

	protected function get_files( string $path ) {

		$source_paths = $source_entries = array();

		$out = array( 'markdown_files' => array(), 'all_paths' => array() );

		$dir = dir( $path );

		while ( false !== ( $entry = $dir->read() ) ) {

			if ( !str_starts_with( $entry, '.' ) ) {

				$path = $dir->path . '/' . $entry;

				if ( is_dir( $path ) ) {

					$out = array_merge( $out, $this->get_files( $path ) );

				} elseif ( str_ends_with( $entry, '.md' ) ) {
			
					$out['markdown_files'][] = $path;

				}

				$out['all_paths'][] = $path;

			}
		
		}
		
		$dir->close();

		return $out;

	}

}
