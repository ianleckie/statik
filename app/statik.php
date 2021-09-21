<?php

// TODO: template wrapping, error handling, documentation, tests

namespace Statik;

class Statik
{

	protected $markdown_files = array();
	protected $parsedown, $source_path, $target_path, $template;

	public function __construct() {

		$this->parsedown = new \Parsedown;

	}
	
	public function generateHTMLFiles( string $source_path, string $target_path, string $template = '' ) {

		$this->source_path = $source_path;
		$this->target_path = $target_path;
		$this->template    = $template;

		return (bool) ( $this->getMarkdownFiles( $source_path ) && $this->resetTargetDir() && $this->makeHTMLFiles() );
		
	}

	protected function getMarkdownFiles( string $path ) {

		$dir = dir( $path );

		while ( false !== ( $entry = $dir->read() ) ) {

			if ( !str_starts_with( $entry, '.' ) ) {

				$path = $dir->path . '/' . $entry;

				if ( is_dir( $path ) ) {

					$this->markdown_files = array_merge( $this->markdown_files, $this->getMarkdownFiles( $path ) );

				} elseif ( str_ends_with( $entry, config('statik')['markdown_extension'] ) ) {
			
					$this->markdown_files[] = $path;

				}

			}
		
		}
		
		$dir->close();

		return (array) $this->markdown_files;

	}

	protected function makeHTMLFiles() {

		foreach ( $this->markdown_files as $path ) {

			$this->makeHTMLFile( $path );

		}

		return (bool) true;

	}

	protected function makeHTMLFile( string $path ) {

		$html = $this->parsedown->text( file_get_contents( $path ) );

		if ( $this->template ) var_dump('TPL');

		$out_path   = str_replace( config('statik')['markdown_extension'], '.html', str_replace( $this->source_path, $this->target_path, $path ) );
		$path_parts = pathinfo( $out_path );
		$out_dir    = $path_parts['dirname'];

		if ( !is_dir( $out_dir ) ) mkdir( $out_dir, 0777, true );

		return (bool) file_put_contents( $out_path, $html );

	}

	protected function resetTargetDir() {

		shell_exec( 'rm -rf ' . $this->target_path );

		return (bool) mkdir( $this->target_path );

	}

}
