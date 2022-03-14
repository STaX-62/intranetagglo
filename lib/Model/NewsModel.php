 <?php

declare(strict_types=1);

namespace OCA\IntranetAgglo\Model;

use OCP\AppFramework\Db\Entity;

/**
 * @method void setTime(int $time)
 * @method int getTime()
 * @method void setAuthor(string $user)
 * @method string getAuthor()
 * @method void setTitle(string $subject)
 * @method string getTitle()
 */

class NewsModel extends Entity {

	// /** @var int */
	// protected $time;

	/** @var string */
	protected $author;

	/** @var string */
	protected $title;

	public function __construct() {
		// $this->addType('time', 'int');
		$this->addType('author', 'string');
		$this->addType('title', 'string');
	}

	public function getParsedSubject(): string {
		return trim(str_replace("\n", ' ', $this->getTitle()));
	}

	// public function getParsedMessage(): string {
	// 	return str_replace(['<', '>', "\n"], ['&lt;', '&gt;', '<br />'], $this->getMessage());
	// }

	/**
	 * @param string $columnName the name of the column
	 * @return string the property name
	 */
	public function columnToProperty($columnName): string {
		// Strip off news_
		if (strpos($columnName, 'news_') === 0) {
			$columnName = substr($columnName, strlen('news_'));
		}

		return parent::columnToProperty($columnName);
	}
}