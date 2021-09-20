<?php

namespace Statik;

class Statik
{

	public function generateHTML( $source_directory, $target_directory, $template ) {

		return $source_directory . ' ' . $target_directory . ' ' . $template;
		
	}

}
