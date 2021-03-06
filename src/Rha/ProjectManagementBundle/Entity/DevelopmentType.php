<?php

namespace Rha\ProjectManagementBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * DevelopmentType
 * @ORM\Entity
 * @ORM\Table(name="development_type", indexes=
 {
 @ORM\Index(name="idx", columns={"name"}),
 @ORM\Index(name="user_idx", columns={"user"})
 }
 )
 */
class DevelopmentType extends \Application\GlobalBundle\Entity\BaseDevelopmentType
{
    /**
     * @ORM\OneToMany(targetEntity="ProjectInformation", mappedBy="DevelopmentType")
     */
    private $project;
    public function __construct()
    {
        $this->project = new ArrayCollection();
    }

    /**
     * Add projects
     *
     * @param  \Rha\ProjectManagementBundle\Entity\ProjectInformation $projects
     * @return DevelopmentType
     */
    public function addProject(\Rha\ProjectManagementBundle\Entity\ProjectInformation $project)
    {
        $this->project[] = $project;

        return $this;
    }

    /**
     * Remove projects
     *
     * @param \Rha\ProjectManagementBundle\Entity\ProjectInformation $projects
     */
    public function removeProject(\Rha\ProjectManagementBundle\Entity\ProjectInformation $project)
    {
        $this->project->removeElement($project);
    }

    /**
     * Get projects
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProjects()
    {
        return $this->project;
    }

    /**
     * Set project
     *
     * @param  \Rha\ProjectManagementBundle\Entity\ProjectInformation $project
     * @return DevelopmentType
     */
    public function setProject(\Rha\ProjectManagementBundle\Entity\ProjectInformation $project = null)
    {
        $this->project[] = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProject()
    {
        return $this->project;
    }
}
