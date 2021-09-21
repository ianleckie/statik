<?php

// TODO: comments, tests
//	make HTML files before resting target directory... save to tmp first

namespace App;

class Statik
{
	protected $markdown_files = array();
	protected $parsedown;
	protected $mustache;
	protected $command;
	protected $source_path;
	protected $target_path;
	protected $template;

	public function __construct() {

		$this->parsedown = new \Parsedown;
		$this->mustache  = new \Mustache_Engine;

	}
	
	public function generateHTMLFiles( object $command, string $source_path, string $target_path, string $template = '' ) {

		$this->command     = $command;
		$this->source_path = $source_path;
		$this->target_path = $target_path;
		$this->template    = $template;

		$this->command->info( "\e[1;32mGenerating HTML files..." );

		return (bool) $this->checkPaths() && $this->checkTemplateFile() && $this->getMarkdownFiles() && $this->resetTargetDir() && $this->makeHTMLFiles();
		
	}

	/**
	 * Ensures that both the source and target paths exist
	 * 
	 * @return bool
	 */
	protected function checkPaths() {
		return (bool) $this->checkPath( $this->source_path ) && $this->checkPath( $this->target_path );
	}

	protected function checkPath( string $path ) {

		$success = true;

		if ( !\File::exists( $path ) ) {
			$success = false;
			$this->command->error('ERROR: Can\'t access path \'' . $path . '\'' );
		}

		return (bool) $success;

	}

	/**
	 * If $this->template is set, make sure the file exists
	 * 
	 * @return bool
	 */
	public function checkTemplateFile() {

		$success = true;

		if ( $this->template != '' ) {

			if ( !\File::exists( $this->template ) ) {
				$success = false;
				$this->command->error('ERROR: Can\'t access template file \'' . $this->template . '\'' );
			} else {
				$this->command->info( "\e[0;35mUsing template: \e[0;37m" . $this->template . "\e[0m" );
			}

		}

		return (bool) $success;
	}

	protected function getMarkdownFiles() {
		return (object) $this->markdown_files = \File::allFiles( $this->source_path );
	}

	protected function resetTargetDir() {
		return (bool) \File::deleteDirectory( $this->target_path ) && \File::makeDirectory( $this->target_path );
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

		if ( $this->createTargetDirectory( $out_file ) && \File::put( $out_file, $html ) ) {

			$this->command->info("\e[0;36mWriting file: \e[0;97m" . $out_file);

			return (bool) true;
		
		}

		return (bool) false;

		return (bool) $this->createTargetDirectory( $out_file ) && \File::put( $out_file, $html );

	}

	protected function createTargetDirectory( string $path ) {

		$out_dir  = pathinfo( $path )['dirname'];

		return (bool) \File::exists( $out_dir ) || \File::makeDirectory( $out_dir, 0777, true );
	
	}

	protected function sourceFileToTargetFile( object $file ) {
		return (string) str_replace( config('statik')['markdown_extension'], '.html', str_replace( $this->source_path, $this->target_path, $file ) );
	}
}
