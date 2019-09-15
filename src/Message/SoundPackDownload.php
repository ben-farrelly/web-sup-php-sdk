<?php
declare(strict_types=1);

namespace Serato\UserProfileSdk\Message;

use Datetime;
use Serato\UserProfileSdk\Message\AbstractMessage;

/**
 * A message representing a user downloading a sound installer.
 */
class SoundPackDownload extends AbstractMessage
{
    const SOUND_PACK_NAME = 'sound_pack_name';
    const DOWNLOAD_AT = 'download_at';

    /**
     * {@inheritdoc}
     */
    public function __construct(int $userId, array $params = [])
    {
        parent::__construct($userId, $params);
        if ($this->getDownloadAt() === null) {
            $this->setDownloadAt((new DateTime())->format(DateTime::ATOM));
        }
    }

    /**
     * Creates a new message instance
     *
     * @param int   $userId      User ID
     * @param array $params      Array of message parameters
     * @return self
     */
    public static function create(int $userId, array $params = []): self
    {
        return new static($userId, $params);
    }

    /**
     * Set the name of the sound download
     *
     * @param string    $sound   Sound name
     * @return self
     */
    public function setSoundName(string $sound): self
    {
        $this->setParam(self::SOUND_PACK_NAME, $sound);
        return $this;
    }

    /**
     * Get the name of the sound download
     *
     * @return null | string
     */
    public function getSoundName(): ?string
    {
        return $this->getParam(self::SOUND_PACK_NAME);
    }

    /**
     * Set the download date of the sound download
     *
     * Date format: DATE_ATOM
     * Example: 2017-08-15T15:52:01+00:00
     * 
     * @param   string  $date
     * @return  self
     */
    public function setDownloadAt(string $date): self
    {
        $this->setParam(self::DOWNLOAD_AT, $date);
        return $this;
    }

    /**
     * Get the download date of the sound download
     *
     * @return null | string
     */
    public function getDownloadAt(): ?string
    {
        return $this->getParam(self::DOWNLOAD_AT);
    }
}
