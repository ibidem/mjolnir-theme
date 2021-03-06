<?php namespace mjolnir\theme;

/**
 * Make is a class that handles mockup. The Mockup class is reserved by the
 * actual Mockup module of the project.
 *
 * @package    mjolnir
 * @category   Mockup
 * @author     Ibidem Team
 * @copyright  (c) 2012 Ibidem Team
 * @license    https://github.com/ibidem/ibidem/blob/master/LICENSE.md
 */
class Mockup extends \app\Instantiatable implements \mjolnir\types\Renderable
{
	use \app\Trait_Renderable;

	/**
	 * @var string
	 */
	protected $type;

	/**
	 * @var array
	 */
	protected $args;

	/**
	 * @var array
	 */
	protected static $counters = array();

	/**
	 * @return static
	 */
	static function instance($type = 'paragraph', array $args = null)
	{
		$instance = parent::instance();
		$instance->type = $type;
		$instance->args = $args;
		return $instance;
	}

	/**
	 * Time from begining to, 5 years in the future.
	 *
	 * @return integer
	 */
	static function time()
	{
		return \rand(0, \time() + \mjolnir\types\Date::year * 5);
	}

	/**
	 * @return static
	 */
	static function name()
	{
		return static::instance('name');
	}

	/**
	 * @return string
	 */
	static function img($width, $height)
	{
		return 'http://placehold.it/'.$width.'x'.$height;
	}

	/**
	 * @return string kitten!
	 */
	static function placekitten($width, $height, $grayscale = false)
	{
		return static::instance('placekitten', [
			'width' => $width,
			'height' => $height,
			'grayscale' => $grayscale,
		]);
	}

	/**
	 * @return string zombie~grrr~brainsss
	 */
	static function placezombie($width, $height, $grayscale = false)
	{
		return static::instance('placezombie', [
			'width' => $width,
			'height' => $height,
			'grayscale' => $grayscale,
		]);
	}

	/**
	 * Similar to Make::img, only produces real life images.
	 *
	 * Categories: abstract, animals, city, food, nightlife, fashion, people,
	 * nature, sports, technics, transport
	 *
	 * Set category to random to get random categories.
	 *
	 * @return static
	 */
	static function lorempixel($width, $height, $category = null, $grayscale = null)
	{
		if ($category == null)
		{
			$config = \app\CFS::config('mjolnir/mockup');
			$category = $config['lorempixel:defaults']['category'];
		}

		if ($grayscale == null)
		{
			$config = \app\CFS::config('mjolnir/mockup');
			$grayscale = $config['lorempixel:defaults']['grayscale'];
		}

		return static::instance('lorempixel', [
			'width' => $width,
			'height' => $height,
			'category' => $category,
			'grayscale' => $grayscale,
		]);
	}

	/**
	 * @return static
	 */
	static function given_name()
	{
		return static::instance('given_name');
	}

	/**
	 * @return static
	 */
	static function family_name()
	{
		return static::instance('family_name');
	}

	/**
	 * @return static
	 */
	static function telephone()
	{
		return static::instance('telephone');
	}

	/**
	 * @return static
	 */
	static function email()
	{
		return static::instance('email');
	}

	/**
	 * @return static
	 */
	static function ssn()
	{
		return static::instance('ssn');
	}

	/**
	 * @return static
	 */
	static function address()
	{
		return static::instance('address');
	}

	/**
	 * @return static
	 */
	static function city()
	{
		return static::instance('city');
	}

	/**
	 * @return static
	 */
	static function paragraph()
	{
		return static::instance('paragraph');
	}

	/**
	 * @return static
	 */
	static function action()
	{
		return function ($action)
			{
				return \app\Controller_Mockup::instance()->action($action);
			};
	}

	/**
	 * @return static
	 */
	static function url($mockup = null)
	{
		return static::instance('url', ['mockup' => $mockup]);
	}

	/**
	 * @return string
	 */
	static function fullurl()
	{
		$url = (\rand(1, 4) === 1 ? '' : 'www.');
		$url .= \app\Mockup::word();
		if (\rand(1,2) === 1)
		{
			$url .= (\rand(1, 2) == 1 ? '-' : '').\app\Mockup::word();

			if (\rand(1,4) === 1)
			{
				$url .= (\rand(1, 2) == 1 ? '-' : '').\app\Mockup::word();
			}
		}

		return $url.'.'.\app\Mockup::rand(['com', 'co.uk', 'co', 'de', 'fr', 'jp', 'kr', 'ro', 'ru', 'eu', 'org']).'/';
	}

	/**
	 * @return static
	 */
	static function counter($id)
	{
		return static::instance('counter', \func_get_args());
	}

	/**
	 * @return static
	 */
	static function title()
	{
		return static::instance('title');
	}

	/**
	 * @return static
	 */
	static function word()
	{
		return static::instance('word');
	}

	/**
	 * @return static
	 */
	static function words($count = 10)
	{
		return static::instance('words', \func_get_args());
	}

	/**
	 * @return static
	 */
	static function rand(array $values)
	{
		$instance = static::instance('rand');
		$instance->args = $values;

		return $instance;
	}

	/**
	 * @return array
	 */
	static function copies($source, $count = null, array $counters = null)
	{
		$count !== null or $count = \rand(-10, 25);

		if ($count < 0)
		{
			$count = 0;
		}

		$copies = array();
		while ($count-- > 0)
		{
			// resolve various counter fields (id, views, time, etc)
			if ($counters)
			{
				foreach ($counters as $counter => &$count_type)
				{
					if (\is_array($count_type)) # random (viewcount, etc)
					{
						$source[$counter] = \rand($count_type[0], $count_type[1]);
					}
					else # incremental counter (index, etc)
					{
						$source[$counter] = $count_type++;
					}
				}
			}

			$copies[] = $source;
		}

		return $copies;
	}

	/**
	 * @return mixed
	 */
	private static function random(array $collection)
	{
		return $collection[\rand(0, \count($collection) - 1)];
	}

	/**
	 * @return string
	 */
	function render()
	{
		$mockup = \app\CFS::config('mjolnir/mockup');
		switch ($this->type)
		{
			case 'lorempixel':
				$width = $this->args['width'];
				$height = $this->args['height'];
				$category = $this->args['category'];
				$grayscale = $this->args['grayscale'];

				$url = 'http://lorempixel.com/';

				if ($grayscale)
				{
					$url .= 'g/';
				}

				$url .= $width.'/'.$height.'/';

				if ($category != 'random')
				{
					$url .= $category.'/'.\rand(1, 10).'/';
				}

				return $url;

			case 'placekitten':
				$width = $this->args['width'];
				$height = $this->args['height'];
				$grayscale = $this->args['grayscale'];

				$url = 'http://placekitten.com/';

				if ($grayscale)
				{
					$url .= 'g/';
				}

				$url .= $width.'/'.$height.'?image='.\rand(1, 16);

				return $url;

			case 'placezombie':
				$width = $this->args['width'];
				$height = $this->args['height'];
				$grayscale = $this->args['grayscale'];

				$url = 'http://placezombies.com/';

				if ($grayscale)
				{
					$url .= 'g/';
				}

				$url .= $width.'x'.$height.'?image='.\rand(1, 10);

				return $url;

			case 'given_name':
				return self::random($mockup['given_names']);

			case 'family_name':
				return self::random($mockup['family_names']);

			case 'name':
				// names are two words
				$family_name = self::random($mockup['family_names']);
				$given_name = self::random($mockup['given_names']);
				return $given_name.' '.$family_name;

			case 'title':
				$words = \rand(4, 20);
				$title = \ucfirst($mockup['words'][\rand(1, \count($mockup['words']) - 1)]);

				while ($words-- > 0)
				{
					$title .= ' '.$mockup['words'][\rand(1, \count($mockup['words']) - 1)];
				}

				return $title;

			case 'word':
				return $mockup['words'][\rand(1, \count($mockup['words']) - 1)];

			case 'url':
				if ($this->args['mockup'] !== null)
				{
					return \app\URL::route('\mjolnir\theme\mockup')->url
						(
							['target' => $this->args['mockup']]
						).$_SERVER['QUERY_STRING'];
				}
				else # no mockup target
				{
					// we generate an unique one so links don't show as seen
					return '//localhost/'.\rand(1, 999999999);
				}

			case 'counter':
				$id = $this->args[0];
				// gurantee counter is initialized
				isset(static::$counters[$id]) or static::$counters[$id] = 1;
				return ''.static::$counters[$id]++;

			case 'words':
				$count = $this->args[0];
				$words = '';
				while ($count-- > 0)
				{
					$words .= self::random($mockup['words']).' ';
				}
				return \rtrim($words, ' ');

			case 'rand':
				$idx = \rand(0, \count($this->args) - 1);
				return $this->args[$idx];

			case 'telephone':
				return '('.\rand(111, 999).') '.\rand(111, 999).'-'.\rand(1111, 9999);

			case 'city':
				return self::random($mockup['cities']);

			case 'email':
				return \strtolower(self::random($mockup['given_names'])).'@'.self::random(array('gmail', 'yahoo', 'hotmail')).'.com';

			case 'ssn':
				$month = \rand(1, 12);
				$day = \rand(1, 30);
				$sector = \rand(1, 52);
				return ''.\rand(1, 9).\rand(0, 99)
					. ($month < 10 ? '0'.$month : $month)
					. ($day < 10 ? '0'.$day : $day)
					. ($sector < 10 ? '0'.$sector : $sector);

			case 'address':
				return 'Str. '.self::random($mockup['family_names'])
					. ', Nr. '.\rand(11, 35)
					. ', Bl. '.\rand(51, 956)
					. ', Ap. '.\rand(1, 25)
					. ', '.self::random($mockup['cities'])
					;

			case 'paragraph':
			default:
				$sentences = \rand(5, 15);
				$paragraph = '';
				while ($sentences-- > 0)
				{
					$words = \rand(4, 20);
					$sentence = \ucfirst($mockup['words'][\rand(1, \count($mockup['words']) - 1)]);

					while ($words-- > 0)
					{
						$sentence .= ' '.$mockup['words'][\rand(1, \count($mockup['words']) - 1)];
					}

					$sentence .= $mockup['punctuation'][\rand(1, \count($mockup['punctuation']) - 1)];
					$sentence .= '  ';
					$paragraph .= $sentence;
				}
				return $paragraph;
		}
	}

	/**
	 * @return string
	 */
	function __toString()
	{
		try
		{
			return $this->render();
		}
		catch (\Exception $e)
		{
			// [!!] __toString can not throw exception in PHP!
			\mjolnir\log_exception($e);
			if (\app\CFS::config('mjolnir/base')['development'])
			{
				return '[ERROR: '.$e->getMessage().']';
			}
			else # public
			{
				return '';
			}
		}
	}

} # class
