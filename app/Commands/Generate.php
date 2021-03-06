<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

class Generate extends Command
{
	/**
	 * The signature of the command.
	 *
	 * @var string
	 */
	protected $signature = 'generate
		{ source_directory : The source directory }
		{ target_directory : The target directory }
		{ --t|template=    : The template to wrap the output in }';

	/**
	 * The description of the command.
	 *
	 * @var string
	 */
	protected $description = "Batch process Markdown to HTML\n";

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		
		$source_directory = $this->argument( 'source_directory' );
		$target_directory = $this->argument( 'target_directory' );
		$template         = (string) $this->option( 'template' );

		$statik = new \App\Statik\Statik;

		$statik->generateHTMLFiles( $this, $source_directory, $target_directory, $template ) && $this->info( "\e[1;32mDONE!" );

	}

	/**
	 * Define the command's schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule $schedule
	 * @return void
	 */
	public function schedule(Schedule $schedule): void
	{
		// $schedule->command(static::class)->everyMinute();
	}
}
