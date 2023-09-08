<?php

namespace RobinIngelbrecht\PHPUnitCoverageTools;

use RobinIngelbrecht\PHPUnitCoverageTools\MinCoverage\MinCoverageResult;
use RobinIngelbrecht\PHPUnitCoverageTools\MinCoverage\ResultStatus;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Output\OutputInterface;

class ConsoleOutput
{
    public function __construct(
        private readonly OutputInterface $output,
    ) {
        $this->output->setDecorated(true);
        $this->output->getFormatter()->setStyle(
            'success',
            new OutputFormatterStyle('green', null, ['bold'])
        );
        $this->output->getFormatter()->setStyle(
            'failed',
            new OutputFormatterStyle('red', null, ['bold'])
        );
        $this->output->getFormatter()->setStyle(
            'warning',
            new OutputFormatterStyle('yellow', null, ['bold'])
        );
        $this->output->getFormatter()->setStyle(
            'bold',
            new OutputFormatterStyle(null, null, ['bold'])
        );
    }

    /**
     * @param \RobinIngelbrecht\PHPUnitCoverageTools\MinCoverage\MinCoverageResult[] $results
     */
    public function print(array $results, ResultStatus $finalStatus): void
    {
        $this->output->writeln('');
        $tableStyle = new TableStyle();
        $tableStyle
            ->setHeaderTitleFormat('<fg=black;bg=yellow;options=bold> %s </>')
            ->setCellHeaderFormat('<bold>%s</bold>');

        $table = new Table($this->output);
        $table
            ->setStyle($tableStyle)
            ->setHeaderTitle('Code coverage results')
            ->setHeaders(['Pattern', 'Expected', 'Actual', ''])
            ->setRows([
                ...array_map(fn (MinCoverageResult $result) => [
                    $result->getPattern(),
                    $result->getExpectedMinCoverage().'%',
                    sprintf('<%s>%s%%</%s>', $result->getStatus()->value, $result->getActualMinCoverage(), $result->getStatus()->value),
                    $result->getNumberOfTrackedLines() > 0 ?
                        sprintf('<bold>%s</bold> of %s lines covered', $result->getNumberOfCoveredLines(), $result->getNumberOfTrackedLines()) :
                        'No lines to track...?',
                ], $results),
                new TableSeparator(),
                [
                    new TableCell(
                        $finalStatus->getMessage(),
                        [
                            'colspan' => 4,
                            'style' => new TableCellStyle([
                                    'align' => 'center',
                                    'cellFormat' => '<'.$finalStatus->value.'>%s</'.$finalStatus->value.'>',
                                ]
                            ),
                        ]
                    ),
                ],
            ]);
        $table->render();
    }
}
