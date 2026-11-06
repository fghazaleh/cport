<?php

declare(strict_types=1);

namespace FG\Support;

final class ArrTest extends WrapperTestCase
{
    public function testAll(): void
    {
        $a = [[1,2,3], fn($v) => $v > 0];
        self::assertSameResult(true, Arr::all(...$a));
        $a = [[1,0], fn($v) => $v > 0];
        self::assertSameResult(false, Arr::all(...$a));
        $a = [[], fn($v) => false];
        self::assertSameResult(true, Arr::all(...$a));
    }

    public function testAny(): void
    {
        $a = [[1,2,3], fn($v) => $v > 2];
        self::assertSameResult(true, Arr::any(...$a));
        $a = [[], fn($v) => true];
        self::assertSameResult(false, Arr::any(...$a));
    }

    public function testArsort(): void
    {
        $a = [[3,1,2]];
        self::assertSameResult([0 => 3,2 => 2,1 => 1], Arr::arsort(...$a));
    }

    public function testAsort(): void
    {
        $a = [[3,1,2]];
        self::assertSameResult([1 => 1,2 => 2,0 => 3], Arr::asort(...$a));
    }

    public function testChangeKeyCase(): void
    {
        $a = [['Ab' => 1,'cD' => 2]];
        self::assertSameResult(array_change_key_case($a[0]), Arr::changeKeyCase(...$a));
        $a = [['Ab' => 1,'cD' => 2], CASE_UPPER];
        self::assertSameResult(array_change_key_case($a[0], $a[1]), Arr::changeKeyCase(...$a));
    }

    public function testChunk(): void
    {
        $a = [[1,2,3,4,5], 2];
        self::assertSameResult(array_chunk($a[0], 2), Arr::chunk(...$a));
        $a = [['a' => 1,'b' => 2,'c' => 3], 2, true];
        self::assertSameResult(array_chunk($a[0], 2, true), Arr::chunk(...$a));
    }

    public function testChunkThrows(): void
    {
        $this->expectException(\ValueError::class);
        Arr::chunk(...[[1], 0]);
    }

    public function testColumn(): void
    {
        $a = [[['id' => 1,'n' => 'a'],['id' => 2,'n' => 'b']], 'n'];
        self::assertSameResult(['a','b'], Arr::column(...$a));
        $a = [[['id' => 1,'n' => 'a'],['id' => 2,'n' => 'b']], 'n', 'id'];
        self::assertSameResult([1 => 'a',2 => 'b'], Arr::column(...$a));
    }

    public function testCombine(): void
    {
        $a = [['a','b'],[1,2]];
        self::assertSameResult(['a' => 1,'b' => 2], Arr::combine(...$a));
    }

    public function testCombineThrows(): void
    {
        $this->expectException(\ValueError::class);
        Arr::combine(...[['a'],[1,2]]);
    }

    public function testContains(): void
    {
        $a = [[1,2,3], '2'];
        self::assertSameResult(true, Arr::contains(...$a));
        $a = [[1,2,3], '2', true];
        self::assertSameResult(false, Arr::contains(...$a));
    }

    public function testCount(): void
    {
        $a = [[1,2,3]];
        self::assertSameResult(3, Arr::count(...$a));
        $a = [[[1,2],[3]], COUNT_RECURSIVE];
        self::assertSameResult(5, Arr::count(...$a));
    }

    public function testCountValues(): void
    {
        $a = [['a','b','a',1,1]];
        self::assertSameResult(array_count_values($a[0]), Arr::countValues(...$a));
    }

    public function testDiff(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_diff($a[0], $a[1], $a[2]), Arr::diff(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_diff($a[0], $a[1]), Arr::diff(...$a));
    }

    public function testDiffAssoc(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_diff_assoc($a[0], $a[1], $a[2]), Arr::diffAssoc(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_diff_assoc($a[0], $a[1]), Arr::diffAssoc(...$a));
    }

    public function testDiffKey(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_diff_key($a[0], $a[1], $a[2]), Arr::diffKey(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_diff_key($a[0], $a[1]), Arr::diffKey(...$a));
    }

    public function testDiffUassoc(): void
    {
        $a = [['a' => 1,'B' => 2,'c' => 3],['A' => 1,'b' => 9], 'strcasecmp'];
        self::assertSameResult(array_diff_uassoc($a[0], $a[1], $a[2]), Arr::diffUassoc(...$a));
    }

    public function testDiffUkey(): void
    {
        $a = [['a' => 1,'B' => 2,'c' => 3],['A' => 1,'b' => 9], 'strcasecmp'];
        self::assertSameResult(array_diff_ukey($a[0], $a[1], $a[2]), Arr::diffUkey(...$a));
    }

    public function testFill(): void
    {
        $a = ['x', 3];
        self::assertSameResult(array_fill(0, 3, 'x'), Arr::fill(...$a));
        $a = ['x', 2, 5];
        self::assertSameResult(array_fill(5, 2, 'x'), Arr::fill(...$a));
    }

    public function testFillKeys(): void
    {
        $a = [['a','b'], 0];
        self::assertSameResult(array_fill_keys($a[0], 0), Arr::fillKeys(...$a));
    }

    public function testFilter(): void
    {
        $a = [[1,0,2,null,3]];
        self::assertSameResult(array_filter($a[0]), Arr::filter(...$a));
        $a = [[1,2,3,4], fn($x) => $x % 2 === 0];
        self::assertSameResult(array_filter($a[0], $a[1]), Arr::filter(...$a));
        $a = [['a' => 1,'b' => 2], fn($k) => $k === 'b', ARRAY_FILTER_USE_KEY];
        self::assertSameResult(['b' => 2], Arr::filter(...$a));
        $a = [['a' => 1,'b' => 2], fn($v, $k) => $v > 1 && $k === 'b', ARRAY_FILTER_USE_BOTH];
        self::assertSameResult(['b' => 2], Arr::filter(...$a));
    }

    public function testFind(): void
    {
        $a = [[1,2,3,4], fn($v) => $v > 2];
        self::assertSameResult(3, Arr::find(...$a));
        $a = [[1,2], fn($v) => $v > 5];
        self::assertSameResult(null, Arr::find(...$a));
    }

    public function testFindKey(): void
    {
        $a = [['a' => 1,'b' => 5], fn($v) => $v > 2];
        self::assertSameResult('b', Arr::findKey(...$a));
        $a = [[1], fn($v) => $v > 5];
        self::assertSameResult(null, Arr::findKey(...$a));
    }

    public function testFirst(): void
    {
        $a = [[7,8,9]];
        self::assertSameResult(7, Arr::first(...$a));
        $a = [['x' => 1]];
        self::assertSameResult(1, Arr::first(...$a));
        $a = [[]];
        self::assertSameResult(null, Arr::first(...$a));
    }

    public function testFlip(): void
    {
        $a = [['a' => 1,'b' => 2]];
        self::assertSameResult(array_flip($a[0]), Arr::flip(...$a));
    }

    public function testFromIterable(): void
    {
        $a = [(static fn() => yield from ['a' => 1,'b' => 2])()];
        self::assertSameResult(['a' => 1,'b' => 2], Arr::fromIterable(...$a));
        $a = [new \ArrayIterator([1,2]), false];
        self::assertSameResult([1,2], Arr::fromIterable(...$a));
    }

    public function testImplode(): void
    {
        $a = [['a','b','c'], ','];
        self::assertSameResult('a,b,c', Arr::implode(...$a));
        $a = [[1,2,3]];
        self::assertSameResult('123', Arr::implode(...$a));
    }

    public function testIntersect(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_intersect($a[0], $a[1], $a[2]), Arr::intersect(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_intersect($a[0], $a[1]), Arr::intersect(...$a));
    }

    public function testIntersectAssoc(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_intersect_assoc($a[0], $a[1], $a[2]), Arr::intersectAssoc(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_intersect_assoc($a[0], $a[1]), Arr::intersectAssoc(...$a));
    }

    public function testIntersectKey(): void
    {
        $a = [['a' => 1,'b' => 2,'c' => 3,0 => 9],['a' => 1,'x' => 2,'c' => 9],[0 => 9]];
        self::assertSameResult(array_intersect_key($a[0], $a[1], $a[2]), Arr::intersectKey(...$a));
        $a = [[1,2,3,4],[2,4]];
        self::assertSameResult(array_intersect_key($a[0], $a[1]), Arr::intersectKey(...$a));
    }

    public function testIntersectUassoc(): void
    {
        $a = [['a' => 1,'B' => 2,'c' => 3],['A' => 1,'b' => 9], 'strcasecmp'];
        self::assertSameResult(array_intersect_uassoc($a[0], $a[1], $a[2]), Arr::intersectUassoc(...$a));
    }

    public function testIntersectUkey(): void
    {
        $a = [['a' => 1,'B' => 2,'c' => 3],['A' => 1,'b' => 9], 'strcasecmp'];
        self::assertSameResult(array_intersect_ukey($a[0], $a[1], $a[2]), Arr::intersectUkey(...$a));
    }

    public function testIsList(): void
    {
        $a = [[1,2,3]];
        self::assertSameResult(true, Arr::isList(...$a));
        $a = [[1 => 1]];
        self::assertSameResult(false, Arr::isList(...$a));
    }

    public function testKeyExists(): void
    {
        $a = [['a' => null], 'a'];
        self::assertSameResult(true, Arr::keyExists(...$a));
        $a = [['a' => null], 'b'];
        self::assertSameResult(false, Arr::keyExists(...$a));
    }

    public function testKeyFirst(): void
    {
        $a = [['x' => 1,'y' => 2]];
        self::assertSameResult('x', Arr::keyFirst(...$a));
        $a = [[]];
        self::assertSameResult(null, Arr::keyFirst(...$a));
    }

    public function testKeyLast(): void
    {
        $a = [['x' => 1,'y' => 2]];
        self::assertSameResult('y', Arr::keyLast(...$a));
        $a = [[]];
        self::assertSameResult(null, Arr::keyLast(...$a));
    }

    public function testKeys(): void
    {
        $a = [['a' => 1,'b' => 2]];
        self::assertSameResult(array_keys($a[0]), Arr::keys(...$a));
    }

    public function testKeysOf(): void
    {
        $a = [[1,'1',2,1], 1];
        self::assertSameResult(array_keys($a[0], $a[1]), Arr::keysOf(...$a));
        $a = [[1,'1',2,1], 1, true];
        self::assertSameResult(array_keys($a[0], $a[1], true), Arr::keysOf(...$a));
    }

    public function testKrsort(): void
    {
        $a = [['b' => 1,'a' => 2,'c' => 0]];
        self::assertSameResult(['c' => 0,'b' => 1,'a' => 2], Arr::krsort(...$a));
    }

    public function testKsort(): void
    {
        $a = [['b' => 1,'a' => 2,'c' => 0]];
        self::assertSameResult(['a' => 2,'b' => 1,'c' => 0], Arr::ksort(...$a));
    }

    public function testLast(): void
    {
        $a = [[7,8,9]];
        self::assertSameResult(9, Arr::last(...$a));
        $a = [['x' => 1,'y' => 2]];
        self::assertSameResult(2, Arr::last(...$a));
        $a = [[]];
        self::assertSameResult(null, Arr::last(...$a));
    }

    public function testMap(): void
    {
        $a = [[1,2,3], fn($x) => $x * 2];
        self::assertSameResult(array_map($a[1], $a[0]), Arr::map(...$a));
        $a = [['a' => 1,'b' => 2], fn($x) => $x + 1];
        self::assertSameResult(['a' => 2,'b' => 3], Arr::map(...$a));
        $a = [[1,2], fn($x, $y) => $x + $y, [10,20]];
        self::assertSameResult([11,22], Arr::map(...$a));
        $a = [[1,2], null, ['a','b']];
        self::assertSameResult([[1,'a'],[2,'b']], Arr::map(...$a));
    }

    public function testMerge(): void
    {
        $a = [[1,2],[3],['a' => 1]];
        self::assertSameResult(array_merge($a[0], $a[1], $a[2]), Arr::merge(...$a));
        $a = [[1]];
        self::assertSameResult([1], Arr::merge(...$a));
    }

    public function testMergeRecursive(): void
    {
        $a = [['a' => [1],'b' => 2],['a' => [3],'b' => 4]];
        self::assertSameResult(array_merge_recursive($a[0], $a[1]), Arr::mergeRecursive(...$a));
    }

    public function testNatcasesort(): void
    {
        $a = [['IMG12','img10','Img2']];
        self::assertSameResult([2 => 'Img2',1 => 'img10',0 => 'IMG12'], Arr::natcasesort(...$a));
    }

    public function testNatsort(): void
    {
        $a = [['img12','img10','img2','img1']];
        self::assertSameResult([3 => 'img1',2 => 'img2',1 => 'img10',0 => 'img12'], Arr::natsort(...$a));
    }

    public function testPad(): void
    {
        $a = [[1,2], 4, 0];
        self::assertSameResult(array_pad($a[0], 4, 0), Arr::pad(...$a));
        $a = [[1,2], -4, 0];
        self::assertSameResult(array_pad($a[0], -4, 0), Arr::pad(...$a));
    }

    public function testPop(): void
    {
        $a = [[1,2,3]];
        self::assertSameResult([1,2], Arr::pop(...$a));
        $a = [[]];
        self::assertSameResult([], Arr::pop(...$a));
    }

    public function testProduct(): void
    {
        $a = [[2,3,4]];
        self::assertSameResult(24, Arr::product(...$a));
        $a = [[]];
        self::assertSameResult(1, Arr::product(...$a));
    }

    public function testPush(): void
    {
        $a = [[1,2],3,4];
        self::assertSameResult([1,2,3,4], Arr::push(...$a));
        $a = [['a' => 1],2];
        self::assertSameResult(['a' => 1,0 => 2], Arr::push(...$a));
    }

    public function testRand(): void
    {
        $a = [[10,20,30]];
        $this->assertContains(Arr::rand(...$a), [0,1,2]);
        $a = [[10,20,30], 3];
        self::assertSameResult([0,1,2], Arr::rand(...$a));
    }

    public function testRandThrows(): void
    {
        $this->expectException(\ValueError::class);
        Arr::rand(...[[], 1]);
    }

    public function testRange(): void
    {
        $a = [1, 5];
        self::assertSameResult(range(1, 5), Arr::range(...$a));
        $a = [0, 10, 5];
        self::assertSameResult(range(0, 10, 5), Arr::range(...$a));
        $a = ['a','e'];
        self::assertSameResult(range('a', 'e'), Arr::range(...$a));
    }

    public function testReduce(): void
    {
        $a = [[1,2,3], fn($c, $i) => $c + $i, 0];
        self::assertSameResult(6, Arr::reduce(...$a));
        $a = [[], fn($c, $i) => $c + $i];
        self::assertSameResult(null, Arr::reduce(...$a));
    }

    public function testReplace(): void
    {
        $a = [[1,2,3],[1 => 'b'],[3 => 'd']];
        self::assertSameResult([1,'b',3,'d'], Arr::replace(...$a));
    }

    public function testReplaceRecursive(): void
    {
        $a = [['a' => [1,2]],['a' => [1 => 'b']]];
        self::assertSameResult(['a' => [1,'b']], Arr::replaceRecursive(...$a));
    }

    public function testReverse(): void
    {
        $a = [[1,2,3]];
        self::assertSameResult([3,2,1], Arr::reverse(...$a));
        $a = [[1,2,3], true];
        self::assertSameResult([2 => 3,1 => 2,0 => 1], Arr::reverse(...$a));
    }

    public function testRsort(): void
    {
        $a = [[3,1,2]];
        self::assertSameResult([3,2,1], Arr::rsort(...$a));
    }

    public function testSearch(): void
    {
        $a = [['a' => 1,'b' => 2], 2];
        self::assertSameResult('b', Arr::search(...$a));
        $a = [[1,2,3], 9];
        self::assertSameResult(null, Arr::search(...$a));
        $a = [[0,1], 0];
        self::assertSameResult(0, Arr::search(...$a));
    }

    public function testShift(): void
    {
        $a = [[1,2,3]];
        self::assertSameResult([2,3], Arr::shift(...$a));
        $a = [['a' => 1,5 => 2]];
        self::assertSameResult([0 => 2], Arr::shift(...$a));
    }

    public function testShuffle(): void
    {
        $a = [[1,2,3,4,5]];
        $this->assertEqualsCanonicalizing($a[0], Arr::shuffle(...$a));
    }

    public function testSlice(): void
    {
        $a = [[1,2,3,4,5], 1, 2];
        self::assertSameResult([2,3], Arr::slice(...$a));
        $a = [[1,2,3,4,5], -2];
        self::assertSameResult([4,5], Arr::slice(...$a));
        $a = [[5 => 'a',6 => 'b',7 => 'c'], 1, 2, true];
        self::assertSameResult([6 => 'b',7 => 'c'], Arr::slice(...$a));
    }

    public function testSort(): void
    {
        $a = [[3,1,2]];
        self::assertSameResult([1,2,3], Arr::sort(...$a));
        $a = [['10','9','2','1']];
        self::assertSameResult(['1','2','9','10'], Arr::sort(...$a));
    }

    public function testSplice(): void
    {
        $a = [[1,2,3,4], 1, 2];
        self::assertSameResult([1,4], Arr::splice(...$a));
        $a = [[1,2,3,4], 1, 2, ['a','b','c']];
        self::assertSameResult([1,'a','b','c',4], Arr::splice(...$a));
        $a = [[1,2,3], -1];
        self::assertSameResult([1,2], Arr::splice(...$a));
    }

    public function testSum(): void
    {
        $a = [[1,2,3.5]];
        self::assertSameResult(6.5, Arr::sum(...$a));
        $a = [[]];
        self::assertSameResult(0, Arr::sum(...$a));
    }

    public function testUasort(): void
    {
        $a = [[3,1,2], fn($x, $y) => $x <=> $y];
        self::assertSameResult([1 => 1,2 => 2,0 => 3], Arr::uasort(...$a));
    }

    public function testUdiff(): void
    {
        $a = [['a' => 'X','b' => 'y','c' => 'Z'],['a' => 'x','b' => 'Q'], 'strcasecmp'];
        self::assertSameResult(array_udiff($a[0], $a[1], $a[2]), Arr::udiff(...$a));
    }

    public function testUdiffAssoc(): void
    {
        $a = [['a' => 'X','b' => 'y','c' => 'Z'],['a' => 'x','b' => 'Q'], 'strcasecmp'];
        self::assertSameResult(array_udiff_assoc($a[0], $a[1], $a[2]), Arr::udiffAssoc(...$a));
    }

    public function testUdiffUassoc(): void
    {
        $a = [['a' => 'X','b' => 'y'],['A' => 'x','b' => 'Q'], 'strcasecmp', 'strcasecmp'];
        self::assertSameResult(array_udiff_uassoc($a[0], $a[1], $a[2], $a[3]), Arr::udiffUassoc(...$a));
    }

    public function testUintersect(): void
    {
        $a = [['a' => 'X','b' => 'y','c' => 'Z'],['a' => 'x','b' => 'Q'], 'strcasecmp'];
        self::assertSameResult(array_uintersect($a[0], $a[1], $a[2]), Arr::uintersect(...$a));
    }

    public function testUintersectAssoc(): void
    {
        $a = [['a' => 'X','b' => 'y','c' => 'Z'],['a' => 'x','b' => 'Q'], 'strcasecmp'];
        self::assertSameResult(array_uintersect_assoc($a[0], $a[1], $a[2]), Arr::uintersectAssoc(...$a));
    }

    public function testUintersectUassoc(): void
    {
        $a = [['a' => 'X','b' => 'y'],['A' => 'x','b' => 'Q'], 'strcasecmp', 'strcasecmp'];
        self::assertSameResult(array_uintersect_uassoc($a[0], $a[1], $a[2], $a[3]), Arr::uintersectUassoc(...$a));
    }

    public function testUksort(): void
    {
        $a = [['b' => 1,'a' => 2], fn($x, $y) => $x <=> $y];
        self::assertSameResult(['a' => 2,'b' => 1], Arr::uksort(...$a));
    }

    public function testUnique(): void
    {
        $a = [[1,'1',2,2,'a','a']];
        self::assertSameResult(array_unique($a[0]), Arr::unique(...$a));
        $a = [[1,'1',2], SORT_REGULAR];
        self::assertSameResult(array_unique($a[0], SORT_REGULAR), Arr::unique(...$a));
    }

    public function testUnshift(): void
    {
        $a = [[3],1,2];
        self::assertSameResult([1,2,3], Arr::unshift(...$a));
    }

    public function testUsort(): void
    {
        $a = [[3,1,2], fn($x, $y) => $x <=> $y];
        self::assertSameResult([1,2,3], Arr::usort(...$a));
    }

    public function testValues(): void
    {
        $a = [['a' => 1,'b' => 2]];
        self::assertSameResult(array_values($a[0]), Arr::values(...$a));
    }

    public function testWalk(): void
    {
        $a = [[1,2,3], function (&$v, $k) {
            $v = $v * 2;
        }];
        self::assertSameResult([2,4,6], Arr::walk(...$a));
        $a = [[1,2], function (&$v, $k, $arg) {
            $v = $v + $arg;
        }, 10];
        self::assertSameResult([11,12], Arr::walk(...$a));
    }

    public function testWalkRecursive(): void
    {
        $a = [[1,[2,3]], function (&$v, $k) {
            $v = $v * 2;
        }];
        self::assertSameResult([2,[4,6]], Arr::walkRecursive(...$a));
    }
}
