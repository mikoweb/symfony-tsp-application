import { LocationDto } from '@app/module/tsp/ui/dto/location-dto';

export class SolveRequestDto {
  constructor(
    public readonly locations: LocationDto[],
    public readonly iterations: number,
    public readonly initialLocationIndex: number,
    public readonly alpha: number,
    public readonly beta: number,
    public readonly distanceCoefficient: number,
    public readonly evaporation: number,
    public readonly antFactor: number,
    public readonly c: number,
  ) {}
}
