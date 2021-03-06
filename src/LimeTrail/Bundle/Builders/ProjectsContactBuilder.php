<?php

namespace LimeTrail\Bundle\Builders;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thrace\DataGridBundle\DataGrid\DataGridFactoryInterface;
use Thrace\DataGridBundle\DataGrid\CustomButton;

/**
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectsContactBuilder
{
    const IDENTIFIER = 'projects_contact';
    protected $factory;
    protected $translator;
    protected $router;
    protected $em;
    protected $container;

    public function __construct(DataGridFactoryInterface $factory,
             TranslatorInterface $translator,
             RouterInterface $router,
             EntityManager $em,
             ContainerInterface $container)
    {
        $this->factory = $factory;
        $this->translator = $translator;
        $this->router = $router;
        $this->em = $em;
        $this->container = $container;
    }

    public function build(/*$locator*/)
    { /* https://github.com/thrace-project/datagrid-bundle/blob/master/Resources/doc/index.md#installation */

        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);

        $query = 'pc.id, c.id as ProjectAssignments, j.jobRole as JobRole, c.firstName as FirstName, c.middleName as MiddleName,
        c.lastName as LastName, c.jobTitle as JobTitle, c.directPhone as DirectPhone, c.mobilePhone as MobilePhone,
        c.email as Email, pc';

        $gridModeler = $this->container->get('lime_trail_grid_model.provider');
        $gridModeler->createModel($query);

        $dataGrid
            ->setQueryBuilder($this->getQueryBuilder($query))
            ->setCaption($this->translator->trans('Project Contacts'))
            ->setColNames($gridModeler->getColNames())
            ->setColModel($gridModeler->getColModel())
            ->enableAddButton(true)
            ->enableEditButton(true)
            //->enableDeleteButton(true)
            ->setShrinkToFit(false)
            ->setHeight('100%')
            ->setRowNum(50)
            //->setAddBtnUri($this->router->generate('limetrail_contacts_create', array(), true))
        ;

        if ($this->container->get('security.context')->isGranted('ROLE_ADMIN')) {
            $dataGrid->enableDeleteButton(true);
        }

        $dataGrid->addCustomButton(new CustomButton('ExportXls', array(
            'title' => 'Export to Xls',
            'caption' => 'Export',
            'buttonIcon' => 'ui-icon-document',
            'position' => 'last',
            'uri' => $this->router->generate('limetrail_storeinformation_exportgrid',
                      array('grid' => self::IDENTIFIER)
                      ),
            )
          )
        );

        return $dataGrid;
    }

    protected function getQueryBuilder($query)
    {
        $qb = $this->em->getRepository('LimeTrailBundle:ProjectContacts')->createQueryBuilder('pc');

        $qb->select($query)
            ->Join('pc.contact', 'c')
            ->Join('pc.project', 'p')
            ->Join('pc.jobrole', 'j')
        ;

        return $qb;
    }
}
