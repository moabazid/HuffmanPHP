<?php

namespace Mordilion\HuffmanPHP;

trait SortTrait
{
    /**
     * sorts the given array with same algorithm as php7.4
     * the keys of the array will be reset
     *
     * @param mixed[]  $array
     * @param callable $compare
     *
     * @throws \Exception
     */
    public function usort74(array &$array, callable $compare): void
    {
        $this->resetArrayKeys($array);
        $this->quickSort($array, $compare);
    }

    /**
     * sorts the items of the given array with the quick sort algorithm
     *
     * @param array<int, mixed> $array
     * @param callable          $compare
     * @param int               $base
     * @param int|null          $length
     *
     * @throws \Exception
     */
    private function quickSort(array &$array, callable $compare, int $base = 0, $length = null)
    {
        if (is_null($length)) {
            $length = count($array);
        }

        while (true) {
            if ($length <= 16) {
                $this->insertSort($array, $compare, $base, $length);
                return;
            } else {
                $start = $base;
                $end = $start + $length;
                $offset = ($length >> 1);
                $pivot = $start + $offset;

                if (($length >> 10)) {
                    $delta = ($offset >> 1);
                    $this->sort5items($array, $compare, $start, $start + $delta, $pivot, $pivot + $delta, $end - 1);
                } else {
                    $this->sort3Items($array, $compare, $start, $pivot, $end - 1);
                }

                $this->swpArrayItems($array, $start + 1, $pivot);

                $pivot = $start + 1;
                $i = $pivot + 1;
                $j = $end - 1;

                while (true) {
                    $item1 = $array[$pivot];
                    $item2 = $array[$i];
                    $result = call_user_func($compare, $item1, $item2);
                    $this->checkCompareResult($result);

                    while ($result > 0) {
                        $i += 1;

                        if ($i == $j) {
                            break 2;
                        }

                        $item1 = $array[$pivot];
                        $item2 = $array[$i];
                        $result = call_user_func($compare, $item1, $item2);
                        $this->checkCompareResult($result);
                    }

                    $j -= 1;
                    if ($j == $i) {
                        break;
                    }

                    $item1 = $array[$j];
                    $item2 = $array[$pivot];
                    $result = call_user_func($compare, $item1, $item2);
                    $this->checkCompareResult($result);

                    while ($result > 0) {
                        $j -= 1;
                        if ($j == $i) {
                            break 2;
                        }

                        $item1 = $array[$j];
                        $item2 = $array[$pivot];
                        $result = call_user_func($compare, $item1, $item2);
                        $this->checkCompareResult($result);
                    }

                    $this->swpArrayItems($array, $i, $j);

                    $i += 1;
                    if ($i == $j) {
                        break;
                    }
                }

                $this->swpArrayItems($array, $pivot, $i - 1);

                if (($i - 1 - $start) < ($end - $i)) {
                    $this->quickSort($array, $compare, $start, ($i - $start - 1));
                    $base   = $i;
                    $length = $end - $i;
                } else {
                    $this->quicksort($array, $compare, $i, ($end - $i));
                    $length = $i - $start - 1;
                }
            }
        }
    }

    /**
     * reset the keys of the give array
     *
     * @param mixed[] $array
     */
    public function resetArrayKeys(array &$array): void
    {
        $newArray = [];
        $key      = 0;

        foreach ($array as $item)
        {
            $newArray[$key] = $item;
            $key            += 1;
        }

        $array = $newArray;
    }

    /**
     * swaps the position of 2 item in an array
     *
     * @param mixed[] $array
     * @param int|string $i
     * @param int|string $j
     */
    public function swpArrayItems(array &$array, $i, $j)
    {
        $iValue    = $array[$i];
        $array[$i] = $array[$j];
        $array[$j] = $iValue;
    }

    /**
     * sorts 2 items in an array
     *
     * @param mixed[]      $array
     * @param callable   $compare
     * @param int|string $i key of the 1. item
     * @param int|string $j key of the 2. item
     *
     * @throws \Exception
     */
    private function sort2Items(array &$array, callable $compare, $i = 0, $j = 1)
    {
        $item1 = $array[$i];
        $item2 = $array[$j];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if ($result > 0)
        {
            $this->swpArrayItems($array, $i, $j);
        }
    }

    /**
     * sorts 3 items in an array
     *
     * @param mixed[]    $array
     * @param callable   $compare
     * @param int|string $i key of the 1. item
     * @param int|string $j key of the 2. item
     * @param int|string $k key of the 3. item
     *
     * @throws \Exception
     */
    private function sort3Items(array &$array, callable $compare, $i = 0, $j = 1, $k = 2)
    {
        $item1 = $array[$i];
        $item2 = $array[$j];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if (!($result > 0))
        {
            $item1 = $array[$j];
            $item2 = $array[$k];
            $result = call_user_func($compare, $item1, $item2);
            $this->checkCompareResult($result);

            if (!($result > 0))
            {
                return;
            }

            $this->swpArrayItems($array, $j, $k);

            $item1 = $array[$i];
            $item2 = $array[$j];
            $result = call_user_func($compare, $item1, $item2);
            $this->checkCompareResult($result);

            if ($result > 0)
            {
                $this->swpArrayItems($array, $i, $j);
            }

            return;
        }

        $item1 = $array[$k];
        $item2 = $array[$j];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if (!($result > 0))
        {
            $this->swpArrayItems($array, $i, $k);

            return;
        }

        $this->swpArrayItems($array, $i, $j);

        $item1 = $array[$j];
        $item2 = $array[$k];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if ($result > 0)
        {
            $this->swpArrayItems($array, $j, $k);
        }
    }

    /**
     * sorts 4 items in an array
     *
     * @param mixed[]    $array
     * @param callable   $compare
     * @param int|string $i key of the 1. item
     * @param int|string $j key of the 2. item
     * @param int|string $k key of the 3. item
     * @param int|string $l key of the 4. item
     *
     * @throws \Exception
     */
    private function sort4Items(array &$array, callable $compare, $i = 0, $j = 1, $k = 2, $l = 3)
    {
        $this->sort3Items($array, $compare, $i, $j, $k);

        $item1 = $array[$k];
        $item2 = $array[$l];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if ($result > 0)
        {
            $this->swpArrayItems($array, $k, $l);

            $item1 = $array[$j];
            $item2 = $array[$k];
            $result = call_user_func($compare, $item1, $item2);
            $this->checkCompareResult($result);

            if ($result > 0)
            {
                $this->swpArrayItems($array, $j, $k);

                $item1 = $array[$i];
                $item2 = $array[$j];
                $result = call_user_func($compare, $item1, $item2);
                $this->checkCompareResult($result);

                if ($result > 0)
                {
                    $this->swpArrayItems($array, $i, $j);
                }
            }
        }
    }

    /**
     * sorts 5 items in an array
     *
     * @param mixed[]    $array
     * @param callable   $compare
     * @param int|string $i key of the 1. item
     * @param int|string $j key of the 2. item
     * @param int|string $k key of the 3. item
     * @param int|string $l key of the 4. item
     * @param int|string $m key of the 5. item
     *
     * @throws \Exception
     */
    private function sort5Items(array &$array, callable $compare, $i = 0, $j = 1, $k = 2, $l = 3, $m = 4)
    {
        $this->sort4Items($array, $compare, $i, $j, $k, $l);

        $item1 = $array[$l];
        $item2 = $array[$m];
        $result = call_user_func($compare, $item1, $item2);
        $this->checkCompareResult($result);

        if ($result > 0)
        {
            $this->swpArrayItems($array, $l, $m);

            $item1 = $array[$k];
            $item2 = $array[$l];
            $result = call_user_func($compare, $item1, $item2);
            $this->checkCompareResult($result);

            if ($result > 0)
            {
                $this->swpArrayItems($array, $k, $l);

                $item1 = $array[$j];
                $item2 = $array[$k];
                $result = call_user_func($compare, $item1, $item2);
                $this->checkCompareResult($result);

                if ($result > 0)
                {
                    $this->swpArrayItems($array, $j, $k);

                    $item1 = $array[$i];
                    $item2 = $array[$j];
                    $result = call_user_func($compare, $item1, $item2);
                    $this->checkCompareResult($result);

                    if ($result > 0)
                    {
                        $this->swpArrayItems($array, $i, $j);
                    }
                }
            }
        }
    }

    /**
     *
     * @param array<int, mixed> $array
     * @param callable          $compare
     * @param int               $base
     * @param null              $length
     *
     * @throws \Exception
     */
    private function insertSort(array &$array, callable $compare, int $base=0, $length = null)
    {
        if (is_null($length)) {
            $length = count($array);
        }

        switch ($length)
        {
            case 0:
            case 1:
                break;
            case 2:
                $this->sort2Items($array, $compare, $base, $base+1);
                break;
            case 3:
                $this->sort3Items($array, $compare, $base, $base+1, $base+2);
                break;
            case 4:
                $this->sort4Items($array, $compare, $base, $base+1, $base+2, $base+3);
                break;
            case 5:
                $this->sort5Items($array, $compare, $base, $base+1, $base+2, $base+3, $base+4);
                break;
            default:
                {
                    $start = $base;
                    $end = $start + $length;
                    $sentry = $start + 6;

                    for ($i = $start + 1; $i < $sentry; $i += 1) {
                        $j = $i - 1;

                        $item1 = $array[$j];
                        $item2 = $array[$i];
                        $result = call_user_func($compare, $item1, $item2);
                        $this->checkCompareResult($result);

                        if (!($result > 0)) {
                            continue;
                        }

                        while ($j != $start) {
                            $j -= 1;

                            $item1 = $array[$j];
                            $item2 = $array[$i];
                            $result = call_user_func($compare, $item1, $item2);
                            $this->checkCompareResult($result);

                            if (!($result > 0)) {
                                $j += 1;
                                break;
                            }
                        }

                        for ($k = $i; $k > $j; $k -= 1) {
                            $this->swpArrayItems($array, $k, $k - 1);
                        }
                    }

                    for ($i = $sentry; $i < $end; $i += 1) {
                        $j = $i - 1;

                        $item1 = $array[$j];
                        $item2 = $array[$i];
                        $result = call_user_func($compare, $item1, $item2);
                        $this->checkCompareResult($result);

                        if (!($result > 0)) {
                            continue;
                        }

                        do {
                            $j -= 2;

                            $item1 = $array[$j];
                            $item2 = $array[$i];
                            $result = call_user_func($compare, $item1, $item2);
                            $this->checkCompareResult($result);

                            if (!($result > 0)) {
                                $j += 1;

                                $item1 = $array[$j];
                                $item2 = $array[$i];
                                $result = call_user_func($compare, $item1, $item2);
                                $this->checkCompareResult($result);

                                if (!($result > 0)) {
                                    $j += 1;
                                }

                                break;
                            }

                            if ($j == $start) {
                                break;
                            }

                            if ($j == $start + 1) {
                                $j -= 1;

                                $item1 = $array[$i];
                                $item2 = $array[$j];
                                $result = call_user_func($compare, $item1, $item2);
                                $this->checkCompareResult($result);

                                if ($result > 0) {
                                    $j += 1;
                                }

                                break;
                            }

                        } while (true);

                        for ($k = $i; $k > $j; $k -= 1) {
                            $this->swpArrayItems($array, $k, $k - 1);
                        }
                    }
                }
                break;
        }
    }

    /**
     * @param mixed $result
     *
     * @throws \Exception
     */
    private function checkCompareResult($result): void
    {
        switch ($result) {
            case -1:
            case 0:
            case 1:
                return;
            default:
                throw new \Exception("The compare function should be return 0, 1 or -1");
        }
    }
}