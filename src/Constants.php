<?php

declare(strict_types=1);

namespace Yivoff\NifCheck;

/**
 * @see http://www.interior.gob.es/web/servicios-al-ciudadano/dni/calculo-del-digito-de-control-del-nif-nie
 */
class Constants
{
    public const VALID_CONTROL_DNI_CHARS = 'TRWAGMYFPDXBNJZSQVHLCKE';
    public const VALID_NIE_PREFIX        = 'XYZ';
    public const VALID_CIF_CONTROL       = 'JABCDEFGHI';
    public const VALID_CIF_PREFIX        = 'ABCDEFGHJNPQRSUVW';
}
