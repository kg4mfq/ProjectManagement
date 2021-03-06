<?php

namespace LimeTrail\Bundle\Builders;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Routing\RouterInterface;
use Thrace\DataGridBundle\DataGrid\DataGridFactoryInterface;
use Thrace\DataGridBundle\DataGrid\CustomButton;

/**
 * ProjectinformationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectinformationBuilder
{
    const IDENTIFIER = 'project_info';
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

    public function build()
    { /* https://github.com/thrace-project/datagrid-bundle/blob/master/Resources/doc/index.md#installation */

        $dataGrid = $this->factory->createDataGrid(self::IDENTIFIER);
        $query = 's.id, s.id as ProjectDates, s.id as Contacts, s.storeNumber, p.Sequence as SequenceNumber,
                p.projectNumber as ProjectNumber, p.canonicalName as CommonName,
                des.name as Description, p.projectPhase as Phase, proto.name as Prototype,
                pc.name as Category, pt.name as ProjectType, dt.name as DevelopmentType, ps.name as Status,
                p.confidential as Confidential, p.combo as ComboSite, p.manageSitesDifferent as ManageDifferently,
                p.sap as SapNumber, py.year as ProgramYear,
                p.prjTotalSquareFootage as TotalSquareFootageEstimated,
                p.actTotalSquareFootage as TotalSquareFootageCalculated, p.storeSquareFootage as SquareFootage,
                p.increaseSquareFootage as IncreasedSquareFootage, s';

        $gridModeler = $this->container->get('lime_trail_grid_model.provider');
        $gridModeler->createModel($query);

        $dataGrid
            ->setQueryBuilder($this->getQueryBuilder($query))
            ->setCaption($this->translator->trans('Project Information'))
            ->setColNames($gridModeler->getColNames())
            ->setColModel($gridModeler->getColModel())
            #->enableSearchButton(true)
            ->enableAddButton(true)
            ->enableEditButton(true)
            ->enableDeleteButton(true)
            ->setHeight("auto")
            ->setShrinkToFit(false)
            ->setDependentDataGrids(array('project_dates'))
        ;

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
        $qb = $this->em->getRepository('LimeTrailBundle:StoreInformation')->createQueryBuilder('s');
        $qb->select($query)
            ->Join('s.projects', 'p')
            ->leftJoin('p.ProjectType', 'pt')
            ->leftJoin('p.DevelopmentType', 'dt')
            ->leftJoin('p.DescriptionOfType', 'des')
            ->leftJoin('p.ProgramYear', 'py')
            ->leftJoin('p.ProgramCategory', 'pc')
            ->leftJoin('p.Prototype', 'proto')
            ->leftJoin('p.ProjectStatus', 'ps')
            #->setMaxResults(5)
            #->groupBy('p.id')
        ;

        return $qb;
    }
}
