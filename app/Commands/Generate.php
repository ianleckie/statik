<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

require_once __DIR__.'/../statik.php';

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
	protected $description = 'Markdown > HTML [source_dir target_dir] [-t template]';

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

		$statik = new \App\Statik;

		$result = $statik->generateHTMLFiles( $source_directory, $target_directory, $template ) ? 'DONE!' : 'ERROR!';

		$this->info( $result );

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
