<?php declare(strict_types=1);
namespace Codewars;

class VersionManager
{
    private $major;
    private $minor;
    private $patch;

    private $history = [];
    
    /**
     * Constructor.
     * 
     * @param string $version
     */
    public function __construct(string $version = "")
    {
        if ($version == "") $version = "0.0.1";

        $this->setVersion($version);
    }

    /**
     * Set version.
     * 
     * @param string $version
     * 
     * @throws \Exception If version is invalid.
     */
    private function setVersion(string $version): void
    {
        $version = explode(".", $version);

        $major = isset($version[0]) ? $version[0] : 0;
        $minor = isset($version[1]) ? $version[1] : 0;
        $patch = isset($version[2]) ? $version[2] : 0;

        if (!is_numeric($major) || !is_numeric($minor) || !is_numeric($patch)) {
            throw new \Exception("Error occured while parsing version!");
        }

        $this->major = (int) $major;
        $this->minor = (int) $minor;
        $this->patch = (int) $patch;
    }

    /**
     * Update major version.
     * Sets minor and patch to 0.
     * 
     * @return self
     */
    public function major(): self
    {
        $this->history[] = $this->release();

        $this->major++;
        $this->minor = 0;
        $this->patch = 0;
        
        return $this;
    }
    
    /**
     * Update minor version.
     * Sets patch to 0.
     * 
     * @return self
     */
    public function minor(): self
    {
        $this->history[] = $this->release();

        $this->minor++;
        $this->patch = 0;
    
        return $this;
    }

    /**
     * Update patch version.
     * 
     * @return self
     */
    public function patch(): self
    {
        $this->history[] = $this->release();

        $this->patch++;
        
        return $this;
    }

    /**
     * Rollback to previous version.
     * 
     * @return self
     * @throws \Exception If there is no previous version.
     */
    public function rollback(): self
    {
        $prev = array_pop($this->history);
        if (null === $prev) throw new \Exception("Cannot rollback!");

        $this->setVersion($prev);
        
        return $this;
    }

    /**
     * Get current version.
     * 
     * @return string
     */
    public function release(): string
    {
        return "{$this->major}.{$this->minor}.{$this->patch}";
    }
}
