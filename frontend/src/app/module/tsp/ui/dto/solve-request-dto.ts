import { LocationDto } from '@app/module/tsp/ui/dto/location-dto';

export class SolveRequestDto {
  constructor(
    public readonly locations: LocationDto[],
  ) {}
}
