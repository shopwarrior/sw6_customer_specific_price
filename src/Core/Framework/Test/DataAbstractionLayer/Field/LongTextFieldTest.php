<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Test\DataAbstractionLayer\Field;

use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowEmptyString;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\AllowHtml;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Flag;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldSerializer\LongTextFieldSerializer;
use Shopware\Core\Framework\DataAbstractionLayer\Write\DataStack\KeyValuePair;
use Shopware\Core\Framework\DataAbstractionLayer\Write\EntityExistence;
use Shopware\Core\Framework\DataAbstractionLayer\Write\WriteParameterBag;
use Shopware\Core\Framework\Test\TestCaseBase\KernelTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Validation\WriteConstraintViolationException;

/**
 * @internal
 */
class LongTextFieldTest extends TestCase
{
    use KernelTestBehaviour;

    /**
     * @dataProvider longTextFieldDataProvider
     *
     * @param bool|string|null $input
     * @param Flag[]           $flags
     */
    public function testLongTextFieldSerializer(string $type, $input, ?string $expected, array $flags = []): void
    {
        $serializer = $this->getContainer()->get(LongTextFieldSerializer::class);

        $name = 'string_' . Uuid::randomHex();
        $data = new KeyValuePair($name, $input, false);

        if ($type === 'writeException') {
            $this->expectException(WriteConstraintViolationException::class);

            try {
                $serializer->encode(
                    $this->getLongTextField($name, $flags),
                    EntityExistence::createEmpty(),
                    $data,
                    $this->getWriteParameterBagMock()
                )->current();
            } catch (WriteConstraintViolationException $e) {
                static::assertSame('/' . $name, $e->getViolations()->get(0)->getPropertyPath());
                /* Unexpected language has to be fixed NEXT-9419 */
                //static::assertSame($expected, $e->getViolations()->get(0)->getMessage());

                throw $e;
            }
        }

        if ($type === 'assertion') {
            static::assertSame(
                $expected,
                $serializer->encode(
                    $this->getLongTextField($name, $flags),
                    EntityExistence::createEmpty(),
                    $data,
                    $this->getWriteParameterBagMock()
                )->current()
            );
        }
    }

    /**
     * @return list<array{string, bool|string|null, ?string, Flag[]}>
     */
    public static function longTextFieldDataProvider(): array
    {
        return [
            ['writeException', '<test>', 'This value should not be blank.', [new Required()]],
            ['writeException', null, 'This value should not be blank.', [new Required()]],
            ['writeException', '', 'This value should not be blank.', [new Required()]],
            ['writeException', true, 'This value should be of type string.', [new Required()]],
            ['assertion', 'test12-B', 'test12-B', [new Required()]],
            ['assertion', null, null, []],
            ['assertion', '<test>', '<test>', [new Required(), new AllowHtml(false)]],
            ['assertion', '', null, []],
            ['assertion', '', '', [new AllowEmptyString()]],
            ['assertion', '', '', [new Required(), new AllowEmptyString()]],
            ['assertion', '<script></script>test12-B', 'test12-B', [new Required(), new AllowHtml()]],
        ];
    }

    private function getWriteParameterBagMock(): WriteParameterBag
    {
        $mockBuilder = $this->getMockBuilder(WriteParameterBag::class);
        $mockBuilder->disableOriginalConstructor();

        return $mockBuilder->getMock();
    }

    /**
     * @param Flag[] $flags
     */
    private function getLongTextField(string $name, array $flags = []): LongTextField
    {
        $field = new LongTextField($name, $name);

        if ($flags) {
            $field->addFlags(new ApiAware(), ...$flags);
        }

        return $field;
    }
}