<?php

namespace Rha\ProjectManagementBundle\Builders;

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
class ProjectBuilder
{
    const IDENTIFIER = 'rhaprojects';
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
        $query = 'si.id, si.storeNumber, p.Sequence as SequenceNumber, p.projectNumber,
                p.canonicalName as ProjectName, c.name as City, s.abbreviation as State, st.name as StoreType,
                pt.name as ProjectType, dt.name as DevelopmentType, proto.name as Prototype,
                p.sap as SapNumber, py.year as ProgramYear, CONCAT(CONCAT(con.firstName,\' \'),con.lastName) as Manager,
                si';

        $gridModeler = $this->container->get('lime_trail_grid_model.provider');
        $gridModeler->createModel($query);

        $rowList = array(30,60,80,120);

        $dataGrid
            ->setQueryBuilder($this->getQueryBuilder($query))
            ->setCaption($this->translator->trans('Projects By Manager'))
            ->setColNames($gridModeler->getColNames())
            ->setColModel($gridModeler->getColModel())
            ->setSortName('City')
            ->setSortOrder('ASC')
            ->enableEditButton(false)
            ->enableSearchButton(true)
            ->setHeight("auto")
            ->setShrinkToFit(false)
            ->setRowNum(30)
            ->setRowList($rowList)
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
        //$date = new \DateTime(date('Y-m-d'));
        $qb = $this->em->getRepository('LimeTrailBundle:StoreInformation')->createQueryBuilder('si');
        $qb->select($query)
            ->Join('si.projects', 'p')
            ->Join('si.city', 'c')
            ->Join('si.state', 's')
            ->Join('si.storeType', 'st')
            ->leftJoin('p.ProjectType', 'pt')
            ->leftJoin('p.DevelopmentType', 'dt')
            ->leftJoin('p.DescriptionOfType', 'des')
            ->leftJoin('p.ProgramYear', 'py')
            ->leftJoin('p.Prototype', 'proto')
            ->leftJoin('p.ProjectStatus', 'ps')
            ->Join('p.dates', 'd')
            ->leftJoin('p.dateOverride', 'o')
            ->leftJoin('p.contacts', 'pc')
            ->Join('pc.contact', 'con')
            ->Join('pc.jobrole', 'j')

        ;

        return $qb;
    }
}
