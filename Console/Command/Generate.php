<?php
/**
 * Copyright © 2020 All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Xigen\HideCacheWarning\Console\Command;

use Magento\Customer\Model\Cache\Type\Notification;
use Magento\Eav\Model\Cache\Type as Eav;
use Magento\Framework\App\Area;
use Magento\Framework\App\Cache\Type;
use Magento\Framework\App\Cache\TypeListInterface;
use Magento\Framework\App\Interception\Cache\CompiledConfig;
use Magento\Framework\App\State;
use Magento\Framework\Console\Cli;
use Magento\Framework\DB\Adapter\DdlCache;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Magento\Integration\Model\Cache\Type as Integration;
use Magento\PageCache\Model\Cache\Type as Fullpage;
use Magento\Webapi\Model\Cache\Type\Webapi;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Generate extends Command
{
    const CACHE_OPTION = "cache";

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var State
     */
    private $state;

    /**
     * @var DateTime
     */
    private $dateTime;

    /**
     * @var TypeListInterface
     */
    private $typeList;

    /**
     * @var InputInterface
     */
    private $input;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * Generate constructor.
     * @param LoggerInterface $logger
     * @param State $state
     * @param DateTime $dateTime
     * @param TypeListInterface $typeList
     */
    public function __construct(
        LoggerInterface $logger,
        State $state,
        DateTime $dateTime,
        TypeListInterface $typeList
    ) {
        $this->logger = $logger;
        $this->state = $state;
        $this->dateTime = $dateTime;
        $this->typeList = $typeList;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ) {
        $this->input = $input;
        $this->output = $output;
        $this->state->setAreaCode(Area::AREA_GLOBAL);
        $option = $input->getOption(self::CACHE_OPTION);
        if (!$option || !in_array($option, $this->getCacheCodeArray())) {
            $this->output->writeln((string) __(
                "[%1] <error>Provide valid [-c|--cache CACHE] parameter</error> ",
                $this->dateTime->gmtDate()
            ));
            $table = new Table($this->output);
            $table->setHeaders(['Cache Code']);
            foreach ($this->getCacheCodeArray() as $code) {
                $table->addRow([
                    "<info>{$code}</info>"
                ]);
            }

            $this->output->writeln('');
            $table->render();

            return Cli::RETURN_FAILURE;
        }

        try {
            $this->typeList->invalidate($option);
        } catch (\Exception $e) {
            $this->output->writeln((string) __(
                "[%1] %2",
                $this->dateTime->gmtDate(),
                $e->getMessage()
            ));
            return Cli::RETURN_FAILURE;
        }

        $this->typeList->invalidate($option);

        $this->output->writeln((string) __(
            "[%1] Successfully invalidated : %2",
            $this->dateTime->gmtDate(),
            $option
        ));

        return Cli::RETURN_SUCCESS;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->setName("xigen:hidecachewarning:generate");
        $this->setDescription("Generate cache warning");
        $this->setDefinition([
            new InputOption(self::CACHE_OPTION, "-c", InputOption::VALUE_REQUIRED, "Cache to invalidate")
        ]);
        parent::configure();
    }

    /**
     * Cache code array
     * @return array
     */
    protected function getCacheCodeArray()
    {
        return [
            Type\Config::TYPE_IDENTIFIER, // config
            Type\Layout::TYPE_IDENTIFIER, // layout
            Type\Block::TYPE_IDENTIFIER, // block_html
            Type\Collection::TYPE_IDENTIFIER, // collections
            Type\Reflection::TYPE_IDENTIFIER, // reflection
            DdlCache::TYPE_IDENTIFIER, // db_ddl
            CompiledConfig::TYPE_IDENTIFIER, // compiled_config
            Eav::TYPE_IDENTIFIER, // eav
            Notification::TYPE_IDENTIFIER, // customer_notification
            Integration::TYPE_IDENTIFIER, // config_integration
            Fullpage::TYPE_IDENTIFIER, // full_page
            Webapi::TYPE_IDENTIFIER, // config_webservice
            Type\Translate::TYPE_IDENTIFIER // translate
        ];
    }
}
