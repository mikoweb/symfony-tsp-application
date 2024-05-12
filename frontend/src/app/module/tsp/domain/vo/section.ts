import { Location } from '@app/module/tsp/domain/vo/location';
import { Distance } from '@app/module/tsp/domain/vo/distance';

export class Section {
  constructor(
    public readonly a: Location,
    public readonly b: Location,
    public readonly length: Distance,
  ) {
  }
}
