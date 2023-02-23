<?php

declare(strict_types=1);

namespace App\Model;

use Nette;
use Nette\Http\SessionSection;
use Nette\Security\Passwords;


/**
 * Users management.
 */
final class UserFacade implements Nette\Security\Authenticator
{
	use Nette\SmartObject;

	public const PasswordMinLength = 7;

	private const
		TableName = 'users',
		ColumnId = 'id',
		ColumnUsername = 'username',
		ColumnPasswordHash = 'password',
		ColumnEmail = 'email',
		ColumnAge = 'age',
		ColumnGender = 'gender',
		ColumnGenderSearch = 'gender_search',
		ColumnProfileText = 'profile_text',
		ColumnCountryId = 'country_id',
        ColumnCityId = 'city_id';

	private Nette\Database\Explorer $database;

	private Passwords $passwords;


	public function __construct(Nette\Database\Explorer $database, Passwords $passwords)
	{
		$this->database = $database;
		$this->passwords = $passwords;
	}


	/**
	 * Performs an authentication.
	 * @throws Nette\Security\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Nette\Security\SimpleIdentity
	{
		$row = $this->database->table(self::TableName)
			->where(self::ColumnUsername, $username)
			->fetch();

		if (!$row) {
			throw new Nette\Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!$this->passwords->verify($password, $row[self::ColumnPasswordHash])) {
			throw new Nette\Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		} elseif ($this->passwords->needsRehash($row[self::ColumnPasswordHash])) {
			$row->update([
				self::ColumnPasswordHash => $this->passwords->hash($password),
			]);
		}

		$arr = $row->toArray();
		unset($arr[self::ColumnPasswordHash]);
		return new Nette\Security\SimpleIdentity($row[self::ColumnId], $arr);
	}


	/**
	 * Adds new user.
	 * @throws DuplicateNameException
	 */
	public function add(SessionSection $section): void
	{
		Nette\Utils\Validators::assert($section->get('email'), 'email');
		try {
			$this->database->table(self::TableName)->insert([
				self::ColumnUsername => $section->get('username'),
				self::ColumnPasswordHash => $this->passwords->hash($section->get('password')),
				self::ColumnEmail => $section->get('email'),
				self::ColumnAge => $section->get('age'),
				self::ColumnGender => $section->get('gender'),
				self::ColumnGenderSearch => $section->get('genderSearch'),
				self::ColumnProfileText => $section->get('profileInfo'),
				self::ColumnCountryId => $section->get('countryId'),
				self::ColumnCityId => $section->get('cityId')
			]);
		} catch (Nette\Database\UniqueConstraintViolationException $e) {
			throw new DuplicateNameException;
		}
	}

    public function getGender(int $genderId) {

    }
}



class DuplicateNameException extends \Exception
{
}
