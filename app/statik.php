<?php

// TODO: set markdown extension in config?

namespace Statik;

class Statik
{

	protected $paths = array();
	protected $markdown_extension = '.md';
	
	public function generateHTMLFiles( string $source_path, string $target_path, string $template = '' ) : string {

		$this->getSourcePaths( $source_path );
		
		var_dump( $this->paths );

		return (string) 'DONE!';
		
	}

	protected function getSourcePaths( string $path ) {

		$out = array( 'markdown_files' => array(), 'all' => array() );
		$dir = dir( $path );

		while ( false !== ( $entry = $dir->read() ) ) {

			if ( !str_starts_with( $entry, '.' ) ) {

				$path = $dir->path . '/' . $entry;

				if ( is_dir( $path ) ) {

					$out = array_merge( $out, $this->getSourcePaths( $path ) );

				} elseif ( str_ends_with( $entry, $this->markdown_extension ) ) {
			
					$out['markdown_files'][] = $path;

				}

				$out['all'][] = $path;

			}
		
		}
		
		$dir->close();

		$this->paths = $out;

		return (array) $out;

	}

}
