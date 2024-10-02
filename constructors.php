<?php

class ArrayStatistics
{
    public function __construct(private array $numbers) {}

    private function bubbleSort(): void
    {
        $count = count($this->numbers);
        for ($i = 0; $i < $count; $i++) {
            for ($j = 0; $j < $count - $i - 1; $j++) {
                if ($this->numbers[$j] > $this->numbers[$j + 1]) {
                    [$this->numbers[$j], $this->numbers[$j + 1]] = [$this->numbers[$j + 1], $this->numbers[$j]];
                }
            }
        }
    }

    public function getMedian(): float
    {
        $this->bubbleSort();
        $count = count($this->numbers);
        $middle = floor($count / 2);

        if ($count % 2 === 0) {
            return ($this->numbers[$middle - 1] + $this->numbers[$middle]) / 2.0;
        } else {
            return (float) $this->numbers[$middle];
        }
    }

    public function getLargest(): int
    {
        $this->bubbleSort();
        return end($this->numbers);
    }
}

class StatisticsUser
{
    public function __construct(private ArrayStatistics $arrayStats) {}

    public function displayStatistics(): void
    {
        $median = $this->arrayStats->getMedian();
        $largest = $this->arrayStats->getLargest();

        echo "Median: $median " . PHP_EOL;
        echo "Largest Value: $largest" . PHP_EOL;
    }
}


$numbers = [5, 3, 8, 1, 2, 7];
$arrayStats = new ArrayStatistics($numbers);
$statsUser = new StatisticsUser($arrayStats);
$statsUser->displayStatistics();
