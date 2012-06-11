<?php
/**
 * This file was developed by Nathan <nathan@webr3.org>
*
* Created:     Nath - 18 Aug 2009 01:24:21
* Modified:    SVN: $Id: ObjectConverter.php 343 2009-08-18 12:39:25Z nathan $
* PHP Version: 5.1.6+
*
* @package   org.webr3.php
* @author    Nathan <nathan@webr3.org>
* @version   SVN: $Revision: 343 $
* @link      http://webr3.org/
*/

/**
 * ObjectConverter, will convert any given object instance to an array or stdClass;
* including private, protected, public and inherited properties.
*
* usage:
*  ObjectConverter::toStdClass( $obj );
* 	ObjectConverter::toArray( $obj );
*
* note:
* 	ObjectConverter will show the true internal value of floats, thus:
* 		(float)12.345
* 	will return as
* 		(string)12.3450000000000006394884621840901672840118408203125
*
* If you pass in something other than an object then it's raw form is returned
* which means no change unless the value is a float (see note above).
*
* @package   org.webr3.php
* @author    Nathan <nathan@webr3.org>
* @version   SVN: $Revision: 343 $
*/
class BL_Object_Converter
{
	/** data **/
	private $d = array();
	/** cursor **/
	private $c = 0;
	/** stdClass mode **/
	private $s = FALSE;

	protected function __construct( $stdClassMode = FALSE )
	{
		$this->reset();
		$this->s = (bool)$stdClassMode;
	}

	final public function reset()
	{
		$this->d = array();
		$this->c = 0;
	}

	/**
	 * @final
	 * @throws UnexpectedValueException
	 * @param $string serialized data
	 * @return array
	 */
	final public function convert( $variable )
	{
		$this->d = str_split( trim(serialize($variable)) , 1 );
		switch( $this->d[$this->c] ) {
			case 'O':
			case 'i':
			case 'd':
			case 'b':
			case 'N':
			case 's':
			case 'a':
				return $this->parsePropertyType( );
			default:
				throw new UnexpectedValueException( "unknown type to deserialize {$this->d[($this->c-1)]}" );
			break;
		}
	}

	/**
	 * @final
	 * @return array
	 */
	final private function parseObject()
	{
		/* sanity check data structure */
		$className = $this->parseString();
		$this->expected(':');
		$numProperties = $this->parseInteger();
		$this->expected(':');
		return $this->parseObjectProperties( $numProperties );
	}

	/**
	 * @final
	 * @param $numProperties
	 * @return array
	 */
	final private function parseObjectProperties( $numProperties )
	{
		$o = $this->s ? new stdClass() : array();
		$this->expected('{');
		for( $i=0; $i<$numProperties; $i++ ) {
			$p = $this->parseProperty();
			if( $this->s ) {
				$o->{$p[0]} = $p[1];
			} else {
				$o[$p[0]] = $p[1];
			}
		}
		$this->expected('}');
		return $o;
	}

	/**
	 * @final
	 * @return array
	 */
	final private function parseArray()
	{
		$this->expected(':');
		$elementCount = $this->parseInteger();
		$this->expected(':');
		return $this->parseArrayProperties( $elementCount );
	}

	/**
	 * @final
	 * @param $elementCount
	 * @return array
	 */
	final private function parseArrayProperties( $elementCount )
	{
		$a = array();
		$this->expected('{');
		for( $i=0; $i<$elementCount; $i++ ) {
			$a[$this->parseArrayProperty()] = $this->parseArrayProperty();
		}
		$this->expected('}');
		return $a;
	}

	/**
	 * @final
	 * @return GenericTypedValue
	 */
	final private function parseArrayProperty()
	{
		return $this->parsePropertyType();
	}

	/**
	 * sanity checking function, ensures that the next parameter is what you expect and consumes it
	 *
	 * @final
	 * @throws UnexpectedValueException
	 * @param $char
	 * @return void
	 */
	final private function expected( $char=':') {
		if( $this->d[$this->c++] != $char ) {
			throw new UnexpectedValueException("expected {$char} but got {$this->d[$this->c-1]}");
		}
	}

	/**
	 * @final
	 * @return array
	 */
	final private function parseProperty()
	{
		$this->expected('s');
		$p = array();
		$p[] = $this->parsePropertyName();
		$p[] = $this->parsePropertyType();
		return $p;
	}

	/**
	 * @final
	 * @return string
	 */
	final private function parsePropertyName( )
	{
		$p = '';
		$this->expected(':');
		$l = $this->parseInteger();
		$this->expected(':');
		$this->expected('"');
		$e = 0;
		while( $l-- ) {
			$ch = $this->d[$this->c++];
			if( ord($ch) == 0 ) {
				$e++;
				$l--;
				$ch = $this->d[$this->c++];
			}
			switch( $e ) {
				case 2:
					$p .= $ch;
					break;
			}
		}
		$this->expected('"');
		$this->expected(';');
		return $p;
	}

	/**
	 * @final
	 * @throws UnexpectedValueException
	 * @return mixed
	 */
	final private function parsePropertyType()
	{
		$p = null;
		switch($this->d[$this->c++]) {
			case 'N':
				$p = null;
				$this->expected(';');
				break;
			case 'b':
				$this->expected(':');
				$p = (bool)($this->d[$this->c++]);
				$this->expected(';');
				break;
			case 'i':
				$this->expected(':');
				$p = $this->parseInteger();
				$this->expected(';');
				break;
			case 'd':
				$this->expected(':');
				$p = $this->parseFloat();
				$this->expected(';');
				break;
			case 's':
				$p = $this->parseString();
				$this->expected(';');
				break;
			case 'a':
				$p = $this->parseArray();
				break;
			case 'O':
				$p = $this->parseObject();
				break;
			default:
				throw new UnexpectedValueException( "unknown type to deserialize {$this->d[($this->c -1)]}" );
			break;
		}
		return $p;
	}

	/**
	 * @final
	 * @return string
	 */
	final private function parseString()
	{
		$this->expected(':');
		$l = $this->parseInteger();
		$this->expected(':');
		$this->expected('"');
		$s = '';
		while( $l-- ) {
			$s .= $this->d[$this->c++];
		}
		$this->expected('"');
		return $s;
	}

	/**
	 * returns a string representation of a float, not a float
	 *
	 * @final
	 * @return float
	 */
	final private function parseFloat()
	{
		$s = '';
		while( $this->d[$this->c] != ';' ) {
			$s .= $this->d[$this->c++];
		}
		return $s;
	}

	/**
	 * $i can be prefixed with an option -/+
	 * invalid values are truncated so 345a6172 becomes 345
	 * no checking range validation, it is assumed that $i contains a valid int
	 *
	 * @final
	 * @param $i
	 * @return int
	 */
	final private function parseInteger( ) {
		$cursor = null;
		$result = 0;
		$neg = false;
		switch($this->d[$this->c]) {
			case '-':
				$neg = true;
				$this->c++;
				break;
			case '+':
				$this->c++;
				break;
		}
		while( isset($this->d[$this->c]) ) {
			$cursor = $this->d[$this->c];
			if ( !($cursor >= '0' && $cursor <= '9') ) {
				break;
			}
			$result = $result * 10 + $cursor;
			$this->c++;
		}
		return $neg ? -$result : $result;
	}

	/**
	 * given any php object, returns an array representation of the value together
	 * with all children, private / protected / public properties, inherited etc
	 *
	 * @param $e
	 * @return array
	 */
	final public static function toArray( $e )
	{
		$converter = new self( false );
		$value = $converter->convert( $e );
		$converter->reset(); // sanity clearing of memory
		return $value;
	}

	/**
	 * given any php object, returns a stdClass representation of the value together
	 * with all children, private / protected / public properties, inherited etc
	 *
	 * @param $e
	 * @return stdClass
	 */
	final public static function toStdClass( $e )
	{
		$converter = new self( true );
		$value = $converter->convert( $e );
		$converter->reset(); // sanity clearing of memory
		return $value;
	}

}