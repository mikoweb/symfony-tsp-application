export class DefaultParametersDto {
  constructor(
    public readonly iterations: number,
    public readonly alpha: number,
    public readonly beta: number,
    public readonly distanceCoefficient: number,
    public readonly evaporation: number,
    public readonly antFactor: number,
    public readonly c: number,
  ) {}
}
