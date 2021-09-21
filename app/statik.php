<?php

// TODO: error handling, comments, tests
//	make HTML files before resting target directory... save to tmp first

namespace App;

class Statik
{

	protected $markdown_files = array();
	protected $parsedown, $mustache, $source_path, $target_path, $template;

	public function __construct() {

		$this->parsedown = new \Parsedown;
		$this->mustache  = new \Mustache_Engine;

	}
	
	public function generateHTMLFiles( string $source_path, string $target_path, string $template = '' ) {

		$this->source_path = $source_path;
		$this->target_path = $target_path;
		$this->template    = $template;

		return (bool) $this->checkPaths() && $this->checkTemplateFile() && $this->getMarkdownFiles() && $this->resetTargetDir() && $this->makeHTMLFiles();
		
	}

	protected function makeHTMLFiles() {

		foreach ( $this->markdown_files as $file ) {

			if ( !$this->makeHTMLFile( $file ) ) return (bool) false;

		}

		return (bool) true;

	}

	protected function makeHTMLFile( object $file ) {

		$html = $this->parsedown->text( \File::get( $file ) );

		if ( $this->template ) $html = $this->mustache->render( \File::get( $this->template ), array( 'content' => $html ) );

		$out_file = $this->sourceFileToTargetFile( $file );

		return (bool) $this->createTargetDirectory( $out_file ) && \File::put( $out_file, $html );

	}

	protected function createTargetDirectory( string $path ) {

		$out_dir  = pathinfo( $path )['dirname'];

		return (bool) \File::exists( $out_dir ) || \File::makeDirectory( $out_dir, 0777, true );
	
	}

	/**
	 * Ensures that both the source and target paths exist
	 * 
	 * @return bool
	 */
	protected function checkPaths() {
		return (bool) \File::exists( $this->source_path ) && \File::exists( $this->target_path );
	}

	/**
	 * If $this->template is set, make sure the file exists
	 * 
	 * @return bool
	 */
	protected function checkTemplateFile() {
		return (bool) !( $this->template ) || \File::exists( $this->template );
	}

	protected function getMarkdownFiles() {
		return (object) $this->markdown_files = \File::allFiles( $this->source_path );
	}

	protected function resetTargetDir() {
		return (bool) \File::deleteDirectory( $this->target_path ) && \File::makeDirectory( $this->target_path );
	}

	protected function sourceFileToTargetFile( object $file ) {
		return (string) str_replace( config('statik')['markdown_extension'], '.html', str_replace( $this->source_path, $this->target_path, $file ) );
	}

}
